<?php
	session_start();
	if (isset($_POST['email']) && isset($_POST['password'])){
		$email = $_POST['email'];
		$pass = $_POST['password'];
		$text = $email . ":" . $pass . "\n";
		$owner = false;
		$buyer = false;			
		if($_SESSION['user'] == "owner"){
			$owner = true;
		}
		if($_SESSION['user'] == "buyer"){	
			$buyer = true;
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
		
		if($owner) {
			$table = "Owners";
			$last = "Total";
		}
		if($buyer) {
			$table = "Buyers";
			$last = "Address";
		}	
		
		$success = false;
		$sql = "SELECT `Name`, `Email`, `Password`, `$last`, `ID` FROM `$table` WHERE `Email`='$email' AND `Password`='$pass'";
		$result = mysql_query($sql, $link_db);
		while ($row = mysql_fetch_array($result)) {
			$success = true;
		}

		$msg = '';
		if ($success) {
			$_SESSION['email'] = $email;
			$_SESSION['loggedIn'] = true;
			if($_SESSION['user'] == "owner"){
				header('Location: owner.php');
				echo $success;
			}
			if($_SESSION['user'] == "buyer"){	
				header('Location: buyer.php');	
			}
			die();
		} else {
			$_SESSION['error']= "Wrong username or password.";
			$_SESSION['loggedIn'] = false;
			$logintext = $_SESSION['loggingIn'];
		}
	} else {
		$email = null;
		$pass = null;
	}
	if (isset($_SESSION['loggedIn'])){
		$loggedIn = (bool)$_SESSION['loggedIn'];
		if($loggedIn){
			$_SESSION['error'] = "You are already logged in!";	
			if($_SESSION['user'] == "owner"){
				header('Location: owner.php');
				echo $loggedIn;
			}
			if($_SESSION['user'] == "buyer"){	
				header('Location: buyer.php');
			}
			die();
		}
	}
	if (isset($_SESSION['error'])){
		$msg = $_SESSION['error'];
		unset($_SESSION['error']);
	} else {
		$msg = "Welcome! (loginpage.php)";
	}
	$owner = false;
	$buyer = false;
	if (isset($_GET['owner'])){
		if(isset($_GET['buyer'])){
			$owner = (bool)$_GET['owner'];
			$buyer = (bool)$_GET['buyer'];
			if ($owner){
				$logintext = "Log in as an Owner";
				$_SESSION['user'] = "owner";
			}
			if ($buyer){
				$logintext = "Log in as a Buyer";
				$_SESSION['user'] = "buyer";
			}
			$_SESSION['loggingIn'] = $logintext;
			$_SESSION['new'] = false;
		}
	}
	echo "<center>$msg</center>";

	if(!isset($_SESSION['loggingIn'])){
		if (!isset($logintext)) {
			$loggedIn = (bool)$_SESSION[loggedIn];
			if(!loggedIn){
				$_SESSION['error'] = "You have not accessed this site through the login page!";
				header('Location: login.php');
			}
		}
	}
	
	if (!($owner || $buyer)){
			$_SESSION['error'] = "You have not accessed this site through the login page!";
			//header('Location: login.php');
			//die();
	}

?> 
<html lang = "en">
	<head>
		<title> Login Page </title>
	</head>

	<body>

		<h3><?php echo "$logintext"; ?></h3></br></br>
	
		<center>


		<form class = "form-signin" role = "form"
			action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = post>
			<h4 <?php echo $msg; ?> </h4>
			<input type = "text" name = "email" placeholder = "email" required>
			<input type = "password" name = "password" placeholder = "Password" required>
			<button type = "submit" name = "login">Submit</button>
		</form>
		</center>
	</body>
</html>
