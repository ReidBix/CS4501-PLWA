<?php
	session_start();
	
	if (isset($_SESSION['user'])){
		$user = $_SESSION['user'];
		if($user != "buyer") {	
			$_SESSION['error'] = "You are not logged in as a buyer!";
			header('Location: owner.php');
			die();
		}
	}
	if (!isset($_SESSION['loggedIn'])){
		$_SESSION['error'] = "You are not logged in!";
		header('Location: login.php');
		die();
	}
	if (!isset($_SESSION['checkout'])){
		$_SESSION['error'] = "You can't checkout without having gone through the confirmation page!";
		header('Location: confirm.php');
		die();
	}

	if (isset($_SESSION['email'])){
		$email = $_SESSION['email'];
	} else {
		$email = "Not Available";
	}

	if (isset($_SESSION['error'])){
		$msg = $_SESSION['error'];
		unset($_SESSION['error']);
	} else {
		$msg = "Welcome! (checkout.php)";
	}	

	echo "<center>$msg</center>";	


	$link_db = mysql_connect('localhost', 'mysql_user', 'mysql_pass', 'my_db');
	if (!$link_db){
		die('Could not connect: ' . mysql_error());
	}
	$sql = "USE my_db";	
	if(mysql_query($sql, $link_db)){
		//echo "Using<br>";	
	} else {
		echo "Error, not using! " . mysql_error();
	}

	if(isset($_POST['logout'])){
		session_unset();
		header('Location: login.php');
		die();
	}
	if(isset($_POST['back'])){
		unset($_SESSION['checkout']);
		unset($_SESSION['cart']);
		header('Location: buyer.php');
		die();
	}

	if(isset($_SESSION['cart'])){
		$cart = $_SESSION['cart'];
	} else {
		$_SESSION['cart'] = array();
		$cart = $_SESSION['cart'];
	}

	$size = sizeof($cart);
	
	$sql = "SELECT `Name`, `Email`, `Password`, `Address`, `ID` FROM `Buyers` WHERE `Email`='$email'";
	$result = mysql_query($sql, $link_db);
	$found = false;
	while ($row = mysql_fetch_array($result)) {
		$name = $row["Name"];
		$pass = $row["Password"];
		$addr = $row["Address"];
		$id = $row["ID"];
		$found = true;
	}
	if(!$found){	
		echo "How did you get here?";
	}
?>	
	<style>
	table {
		border-collapse: collapse;
	}
	</style>
	<div align=right><form acton='checkout.php' method = 'post'>
		<input type='submit' name='back' value='Back to Items' />
	</form>
	<form acton='checkout.php' method = 'post'>
		<input type='submit' name='logout' value='Log Out' />
	</form></div>
	<center>
	<h2>Check out Summary</h2>
	<b>Items Purchased</b></br>
	<table border = "1" width = "95%" height = "60%">
<tr align = "center">
	<col width = '65%'>
	<col width = '35%'>
	<th>Product</th>
	<th>Price</th>
</tr>
</br>
<?php
	$total= 0;
	foreach($cart as $value){
		$sql = "SELECT `Name`, `Number`, `Description`, `Cost`, `Quantity`, `OwnerID` FROM `Items` WHERE `Name`='$value'";
		$result = mysql_query($sql, $link_db);
		while ($row = mysql_fetch_array($result)) {
			$iName = $row["Name"];
			$iDesc = $row["Description"];
			$iCost = $row["Cost"];
			$iQty = $row["Quantity"];
			$iOwner = $row["OwnerID"];
?>
	<tr align = "center">
	<td> <?php echo $iName;?></td>
	<td> $<?php echo $iCost;?></td>
	</tr>
<?php
		}
		$sql = "SELECT `Name`, `Email`, `Password`, `Total`, `ID` FROM `Owners` WHERE `ID`='$iOwner'";
		$result = mysql_query($sql, $link_db);
		while ($row = mysql_fetch_array($result)) {
			$recip = $row["Email"];
			$tot = (int)$row["Total"] + (double)$iCost;
			break;
		}
		$sql = "UPDATE `Owners` SET `Total`='$tot' WHERE `ID`='$iOwner'";
		if(mysql_query($sql, $link_db)){
			//echo "Added cost to owner<br>";		
		} else {
			echo "Error, didn't add cost to owner! " . mysql_error();
		}
		//Doesn't send on localhost
		$to = $recip;
		$subject = "ITEMS SOLD!";
		$message = "You sold item: $iName on the Online Marketplace for $$iCost.";
		$message = wordwrap($message,70, "\r\n");
		mail($to,$subject, $message);
		$total = $total + (double)$iCost;
	}
?>
	</table></br></br>
	<b>Thank you, <?php echo $name; ?>. Your total is $<?php echo $total; ?> and your items will be mailed to <?php echo $addr; ?>.</b>
