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

	if (isset($_SESSION['error'])){
		$msg = $_SESSION['error'];
		unset($_SESSION['error']);
	} else {
		$msg = "Welcome! (buyer.php)";
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
	$_SESSION['loggedIn'] = true;
	

	if(isset($_POST['switch'])){
		$cat = $_POST['catSwitch'];
		$_SESSION['cat'] = $cat;
	}
	if(isset($_SESSION['cat'])){
		$cat = $_SESSION['cat'];
	}

	if(isset($_POST['checkout'])){
		header('Location: confirm.php');
		die();
	}
	
	if(isset($_POST['logout'])){
		$cart = $_SESSION['cart'];
		foreach ($cart as $val){
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
				echo "Error, not added! " . mysql_error();
			}
			if (($key = array_search($val, $cart)) !== false){
				unset($cart[$key]);
			}	
		}
		$_SESSION['cart'] = $cart;
		session_unset();
		header('Location: login.php');
		die();
	}
	
	echo "<center>$msg</center></br>";

	if(!isset($_POST['switch'])){
		if(!isset($_SESSION['cat'])){
			$cat = "Books";
		}
	}

	$catNum = 0;
	if ($cat == "Books") $catNum = 1;
	else if ($cat == "Music") $catNum = 2;
	else if ($cat == "Cars") $catNum = 3;
	$size = 0;
	$email = $_SESSION['email'];


	$sql = "SELECT `Name`, `Email`, `Password`, `Address`, `ID` FROM `Buyers`";
	$result = mysql_query($sql, $link_db);
	$found = false;
	while ($row = mysql_fetch_array($result)) {
		//echo "Name: " . $row["Name"] . " - Email: " . $row["Email"] . " - Password: " . $row["Password"] . " - Address: " . $row["Address"] . " - ID: " . $row["ID"] . "<br>";
		if($email == $row["Email"]){
			$name = $row["Name"];
			$pass = $row["Password"];
			$addr = $row["Address"];
			$id = $row["ID"];
			$found = true;
		}
	}
	if(!$found){	
		echo "How did you get here?";
		$_SESSION['error'] = "Wrong username or password";
		header('Location: loginpage.php');
		die();
	}



	if(isset($_SESSION['cart'])){
		$cart = $_SESSION['cart'];
	} else {
		$_SESSION['cart'] = array();
		$cart = $_SESSION['cart'];
	}
	if(isset($_POST['AddToCart'])){
		$val = $_POST['AddToCart'];
		array_push($cart,$val);
		$_SESSION['cart'] = $cart;
						
		$sql = "SELECT `Name`, `Number`, `Description`, `Cost`, `Quantity`, `OwnerID` FROM `Items`";
		$result = mysql_query($sql, $link_db);
		while ($row = mysql_fetch_array($result)) {
			if($val == $row["Name"]){
				$qty = (int)$row["Quantity"] - 1;
				break;
			}
		}

		$sql = "UPDATE `Items` SET `Quantity`='$qty' WHERE `Name`='$val'";
		if(mysql_query($sql, $link_db)){
			//echo "Removed<br>";		
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
		<input type='submit' name='logout' value='Logout' />
	</form></div>
	<h3>Welcome to the online store, <?php echo $name;?>!</h3></br>
	You're viewing the <b><?php echo $cat; ?></b> category </br>
	
	<div align=right><b>Number of items in cart: <?php echo $size; ?></b></div>
	<center>
	<table border = "1" width = "95%" height = "60%">
	<br/><br/>
	<col width = '15%'>
	<col width = '50%'>
	<col width = '10%'>
	<col width = '15%'>
	<col width = '10%'>
<tr align = "center">
	<th>Item</th>
	<th>Description</th>
	<th>Price</th>
	<th>Number Left</th>
	<th>      </th>
</tr>
<?php
	$sql = "SELECT `Name`, `Number`, `Description`, `Cost`, `Quantity`, `OwnerID` FROM `Items`";
	$result = mysql_query($sql, $link_db);
	while ($row = mysql_fetch_array($result)) {
		if($catNum == $row["Number"] && $row["Quantity"] > 0){
			$iName = $row["Name"];
			$iDesc = $row["Description"];
			$iCost = $row["Cost"];
			$iQty = $row["Quantity"];
			$iOwner = $row["OwnerID"];
?>
	<tr align = "center">
	<td> <?php echo $iName;?> </td>
	<td> <?php echo $iDesc;?> </td>
	<td> $<?php echo $iCost;?> </td>
	<td> <?php echo $iQty;?> </td> 
	<td> <form action='' method='post'>
		<button name = 'AddToCart' value = '<?php echo $iName?>'>Add To Cart</button>
		</form>
	</td>
	</tr>
<?php
		}
	}
	$sBooks = 0;
	$sMusic = 0;
	$sCars = 0;
	if ($cat == "Books"){
		$sBooks = 1;
	}
	if ($cat == "Music"){
		$sMusic = 1;
	}
	if ($cat == "Cars"){
		$sCars = 1;
	}
?>
	<tr align='center'> 
	<td> </td>
	<td> <form method = 'post'>
		Item Category<select name="catSwitch" id=oCat>
		<option value="Books"> Books </option>
		<option value="Music"> Music </option>
		<option value="Cars"> Cars </option>
		</select>
		<input type ='submit' name='switch' value='Switch Category' />
		</form>
<script>
	document.getElementById('oCat').value=
	<?php 
		if($sBooks) echo json_encode("Books");
		if($sMusic) echo json_encode("Music");
		if($sCars) echo json_encode("Cars");
	?>
	;
</script>
	</td>
	<td> <form acton='checkout.php' method = 'post'>
		<input type='submit' name='checkout' value='Checkout' />
		</form>
	 </td>
	<td> </td>
	</tr>
<?php
	echo "</center>";
?>
