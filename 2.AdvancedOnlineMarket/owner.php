<?php
	session_start();
	
	if (isset($_SESSION['user'])){
		$user = $_SESSION['user'];
		if($user != "owner") {	
			$_SESSION['error'] = "You are not logged in as an owner!";
			header('Location: buyer.php');
			die();
		}
	}
	if (!isset($_SESSION['loggedIn'])){
		$_SESSION['error'] = "You are not logged in!";
		header('Location: login.php');
		die();
	}


	if(isset($_POST['logout'])){
		session_unset();
		header('Location: login.php');
		die();
	}

	$email = $_SESSION['email'];
	$_SESSION['loggedIn'] = true;

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

	$sql = "SELECT `Name`, `Email`, `Password`, `Total`, `ID` FROM `Owners`";
	$result = mysql_query($sql, $link_db);
	$found = false;
	while ($row = mysql_fetch_array($result)) {
		//echo "Name: " . $row["Name"] . " - Email: " . $row["Email"] . " - Password: " . $row["Password"] . " - $last: " . $row[$last] . " - ID: " . $row["ID"] . "<br>";
		if($email == $row["Email"]){
			$name = $row["Name"];
			$pass = $row["Password"];
			$total = $row["Total"];
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

	$fail = 0;
	$error1 = 1;
	$error2 = 1;
	$error3 = 1;
	$error4 = 1;
	$error5 = 1;
	$msg = "Item added successfully!";
	$errormsg = 1;
	if(isset($_POST['iSubmit'])){
		$iName = $_POST['iName'];	
		if (strlen($iName) > 100) $fail = 1;
		if ($fail == 1) $error1 = 'Length of Item Name must be less than 100 characters!';
		$iCat = $_POST['iCat'];	
		$iDesc = $_POST['iDesc'];	
		if (strlen($iDesc) > 1000) $fail = 2;
		if ($fail == 2) $error2 = 'Length of Item Description must be less than 1000 characters!';
		$iCost = floatval(number_format(floatval($_POST['iCost']), 2, '.', ''));
		if (!is_double($iCost) || $iCost <= 0) $fail = 3;
		if ($fail == 3) $error3 = 'Item Cost must be a number and cannot be 0!';
		$iNum = intval($_POST['iNum']);	
		if (!is_int($iNum) || $iNum <= 0) $fail = 4;
		if ($fail == 4) $error4 = 'Number of Item must be an integer and cannot be 0!';
		$iOwner = $id;
		if ($iCat == "Books") $iCat = 1;
		else if ($iCat == "Music") $iCat = 2;
		else if ($iCat == "Cars") $iCat = 3;
			
		$sql = "SELECT `Name`, `Number`, `Description`, `Cost`, `Quantity`, `OwnerID` FROM `Items` WHERE `Name`='$iName'";
		$result = mysql_query($sql, $link_db);
		$found = false;
		while ($row = mysql_fetch_array($result)) {
			$found = true;
		}
		if ($found) $fail = 5;
		if ($fail == 5) $error5 = 'Item already exists in the marketplace!';
		if ($error1 == 1 && $error2 == 1 && $error3 == 1  && $error4 == 1 && $error5 == 1){
			$sql = "INSERT INTO `Items` (`Name`, `Number`, `Description`, `Cost`, `Quantity`, `OwnerID`) VALUES('$iName', '$iCat', '$iDesc', '$iCost', '$iNum', '$iOwner')";

			if(mysql_query($sql, $link_db)){
				//echo "Items added to<br>";
			} else {
				echo "Error, Items not added to! " . mysql_error();
			}

		} else {
			$errormsg = "";
			if ($error1 == 0){
				$errormsg = $errormsg . $error1 . "<br>";
			}
			if ($error2 == 0){
				$errormsg = $errormsg . $error2 . "<br>";
			}
			if ($error3 == 0){
				$errormsg = $errormsg . $error3 . "<br>";
			}
			if ($error4 == 0){
				$errormsg = $errormsg . $error4 . "<br>";
			}
			if ($error5 == 0){
				$errormsg = $errormsg . $error5 . "<br>";
			}
				$errormsg = $errormsg . "Item not added!<br>";
		}
		if($errormsg != 1){
			$_SESSION['ownermsg'] = $errormsg;
		} else {
			$_SESSION['ownermsg'] = $msg;
		}
	}


	if (isset($_SESSION['error'])){
		$msg = $_SESSION['error'];
		unset($_SESSION['error']);
		echo "<center>$msg</center>";
	} 
	else if (isset($_SESSION['ownermsg'])){
		$msg = $_SESSION['ownermsg'];
		unset($_SESSION['ownermsg']);
		echo "<center>$msg</center>";
	}
	else {
		$msg = "Welcome! (owner.php)";
		echo "<center>$msg</center>";
	}

?> 

<html lang = "en">
	<head>
		<title> Owner Pages </title>
	</head>


	<body>

		<div align=right><form acton='checkout.php' method = 'post'>
			<input type='submit' name='logout' value='Logout' />
		</form></div>
		<h3><?php echo "Total Earned by $name: $$total" ;?></h3></br></br>
	
		<h3>Fill out this form to add an item.</h3></br>

		<form action="owner.php" method="post">
			Item Name:<input type = "text" name = "iName" required></br></br>
			Item Category<select name="iCat">
			<Option selected> Books </Option>
			<Option> Music </Option>
			<Option> Cars </Option>
			</select></br></br>
			Item Description:<input type = "text" name = "iDesc" required></br></br>
			Item Cost:<input type = "text" name = "iCost" required></br></br>
			Number of Item:<input type = "text" name = "iNum" required></br></br>
			<button type = "submit" name = "iSubmit">Submit</button>
		</form>
	</body>
</html>
