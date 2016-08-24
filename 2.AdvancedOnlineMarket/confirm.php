
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

	if (isset($_SESSION['email'])){
		$email = $_SESSION['email'];
	} else {
		$email = "Not Available";
	}

	if (isset($_SESSION['error'])){
		$msg = $_SESSION['error'];
		unset($_SESSION['error']);
	} else {
		$msg = "Welcome! (confirm.php)";
	}	
	echo "<center>$msg</center>";	
	if(isset($_POST['back'])){
		header('Location: buyer.php');
		die();
	}
	if(isset($_POST['confirm'])){
		$_SESSION['checkout'] = true;
		header('Location: checkout.php');
		die();
	}
	
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
	
	if(isset($_SESSION['cart'])){
		$cart = $_SESSION['cart'];
	} else {
		$_SESSION['cart'] = array();
		$cart = $_SESSION['cart'];
	}
	if(isset($_POST['Remove'])){
		$val = $_POST['Remove'];
		if (($key = array_search($val, $cart)) !== false){
			unset($cart[$key]);
		}
		$_SESSION['cart'] = $cart;
		$sql = "SELECT `Name`, `Number`, `Description`, `Cost`, `Quantity`, `OwnerID` FROM `Items` WHERE `Name`='$val'";
		$result = mysql_query($sql, $link_db);
		while ($row = mysql_fetch_array($result)) {
			$qty = (int)$row["Quantity"] + 1;
			break;
		}
		$sql = "UPDATE `Items` SET `Quantity`='$qty' WHERE `Name`='$val'";
		if(mysql_query($sql, $link_db)){
			//echo "Added<br>";		
		} else {
			echo "Error, not removed! " . mysql_error();
		}	
	}
	$size = sizeof($cart);
?>	
	<style>
	table {
		border-collapse: collapse;
	}
	</style>
	<div align=right><form acton='checkout.php' method = 'post'>
		<input type='submit' name='back' value='Back to Items' />
	</form>
	<center>
	<h2>Confirmation Summary</h2>
	<b>Items to be Purchased (<?php echo $size; ?> items)</b></br>
	<table border = "1" width = "95%" height = "60%">
<tr align = "center">
	<col width = '60%'>
	<col width = '30%'>
	<col width = '10%'>
	<th>Product</th>
	<th>Price</th>
	<th> </th>
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
	<td> <form action='' method='post'>
		<button name = 'Remove' value = '<?php echo $iName?>'>Remove Item</button>
		</form>
	</td>
	</tr>
<?php
		}
		$total = $total + (double)$iCost;
	}
?>
	<tr align = "center">
	<td> </td>
	<td> Total: $<?php echo $total; ?></td>
	</table></br></br>
	<center><form acton='checkout.php' method = 'post'>
		<input type='submit' name='confirm' value='Confirm' />
	</form></center>
