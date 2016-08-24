<?php
	if (!isset($_SESSION['start'])){
		session_start();
		$_SESSION['start'] = true;
	}
	if (isset($_SESSION['loggedIn'])){
		unset($_SESSION['loggedIn']);
		$_SESSION['error'] = "You are already logged in!";	
		if($_SESSION['user'] == "owner"){
			header('Location: owner.php');
		}
		if($_SESSION['user'] == "buyer"){	
			header('Location: buyer.php');
		}
		//die();
		$logintext = "Fail";
	}
	if (isset($_SESSION['error'])){
		$msg = $_SESSION['error'];
		unset($_SESSION['error']);
	} else {
		$msg = "Welcome! (login.php)";
	}
	if(!isset($_SESSION['new'])){	
		$_SESSION['new'] = true;
	}

	echo "<center>$msg</center>";
	
	$link_db = mysql_connect('localhost', 'mysql_user', 'mysql_pass', 'my_db');
	if (!$link_db){
		die('Could not connect: ' . mysql_error());
	}
	if(!isset($_COOKIE['cookie'])){
		setcookie("cookie", true, time()+3600);
		$sql = "CREATE DATABASE my_db";
		if(mysql_query($sql, $link_db)){
			//echo "Created<br>";
		} else {
			echo "Error, not created! " . mysql_error();
		}
		$sql = "USE my_db";	
		if(mysql_query($sql, $link_db)){
			//echo "Using<br>";
		} else {
			echo "Error, not using! " . mysql_error();
		}

		$sql = "CREATE TABLE Owners (
		Name VARCHAR(100) NOT NULL,
		Email VARCHAR(100) NOT NULL,
		Password VARCHAR(100) NOT NULL,
		Total FLOAT(20,2) NOT NULL,
		ID INT(10) NOT NULL
		)";
		if(mysql_query($sql, $link_db)){
			//echo "Table Owners created<br>";
		} else {
			echo "Error, table Owners not created! " . mysql_error();
		}
		$ownerlist = file('owners.txt');	
		$ctr = 1;
		foreach($ownerlist as $u) {
			$split = explode("#",$u);
			$ownerName = $split[0];
			$ownerEmail = $split[1];
			$ownerPass = $split[2];
			$ownerTotal = $split[3];
			$sql = "INSERT INTO `Owners` (`Name`, `Email`, `Password`, `Total`, `ID`) VALUES('$ownerName', '$ownerEmail', '$ownerPass', '$ownerTotal', '$ctr')";
			if(mysql_query($sql, $link_db)){
				//echo "Owners added to<br>";
			} else {
				echo "Error, Owners not added to! " . mysql_error();
			}
			$ctr = $ctr + 1;
		}			

		
		$sql = "CREATE TABLE Buyers (
		Name VARCHAR(100) NOT NULL,
		Email VARCHAR(100) NOT NULL,
		Password VARCHAR(100) NOT NULL,
		Address VARCHAR(250) NOT NULL,
		ID INT(10) NOT NULL
		)";
		if(mysql_query($sql, $link_db)){
			//echo "Table Buyers created<br>";
		} else {
			echo "Error, table Buyers not created! " . mysql_error();
		}
		$buyerlist = file('buyers.txt');	
		$ctr = 1;
		foreach($buyerlist as $u) {
			$split = explode("#",$u);
			$buyerName = $split[0];
			$buyerEmail = $split[1];
			$buyerPass = $split[2];
			$buyerAddr = $split[3];
			$sql = "INSERT INTO `Buyers` (`Name`, `Email`, `Password`, `Address`, `ID`) VALUES('$buyerName', '$buyerEmail', '$buyerPass', '$buyerAddr', '$ctr')";
			if(mysql_query($sql, $link_db)){
				//echo "Buyers added to<br>";
			} else {
				echo "Error, Buyers not added to! " . mysql_error();
			}
			$ctr = $ctr + 1;
		}


		$sql = "CREATE TABLE Items (
		Name VARCHAR(100) NOT NULL,
		Number INT(1) NOT NULL,
		Description VARCHAR(1000) NOT NULL,
		Cost FLOAT(20,2) NOT NULL,
		Quantity INT(50) NOT NULL,
		OwnerID INT(10) NOT NULL
		)";
		if(mysql_query($sql, $link_db)){
			//echo "Table Items created<br>";
		} else {
			echo "Error, table Items not created! " . mysql_error();
		}
		$itemlist = file('items.txt');	
		foreach($itemlist as $u) {
			$split = explode("#",$u);
			$itemName = $split[0];
			$itemNum = $split[1];
			$itemDesc = $split[2];
			$itemCost = $split[3];
			$itemQty = $split[4];
			$itemOwn = $split[5];
			$sql = "INSERT INTO `Items` (`Name`, `Number`, `Description`, `Cost`, `Quantity`, `OwnerID`) VALUES('$itemName', '$itemNum', '$itemDesc', '$itemCost', '$itemQty', '$itemOwn')";

			if(mysql_query($sql, $link_db)){
				//echo "Items added to<br>";
			} else {
				echo "Error, Items not added to! " . mysql_error();
			}
		}
		echo "<center>Database created</center>";
	} else {
		if (isset($_POST['clear'])){
			setcookie("cookie", "", time()-3600);
			$sql = 'DROP DATABASE my_db';
			if(mysql_query($sql, $link_db)){
				//echo "Dropped!";
			} else{
				echo "Error, not dropped! " . mysql_error();
			}
			session_unset();
			if(isset($_SESSION['start'])){
				session_start();
				$_SESSION['start'] = true;
			}
			$_SESSION['error'] = "Cleared cookies, sessions variables, and database";
			echo "<script>parent.window.location.reload(true);</script>";
			die();
		}
	}
?> 
<html lang = "en">
	<head>
		<title> Login Choose Page </title>
	</head>

	<body>

	<div align=right><form acton='login.php' method = 'post'>
		<input type='submit' name='clear' value='Clear Cookies' />
	</form></div>
		<h2>Logging in:</h2></br>
		<center>

			<a href="loginpage.php?owner=1&buyer=0">Owner Login</a> </br>
			<a href="loginpage.php?owner=0&buyer=1">Buyer Login</a>
		</center>
	</body>
</html>
