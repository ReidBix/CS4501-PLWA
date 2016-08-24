<?php
	session_start();
	if (isset($_POST['username']) && isset($_POST['password'])){
		$user = $_POST['username'];
		$pass = $_POST['password'];
	} else {
		$user = null;
		$pass = null;
	}
	if (isset($_SESSION['error'])){
		$msg = $_SESSION['error'];
	} else {
		$msg = "Welcome!";
	}
	if (isset($_COOKIE['cookie'])){
		$c = $_COOKIE['cookie'];
	}
	if (!isset($c)){
		setcookie("cookie","You have visited this site 1 previous time",time()+600);
	}
	echo "<center>$msg</center>";
?>

<html lang = "en">
	<head>
		<title> Online Market </title>
	</head>

	<body>
		<center>
		<h1> Online Market </h1>
		<h2> Log in to continue </h2>
		
		<?php
			$text = $user . ":" . $pass . "\n";
			$userlist = file('buyer.txt');
			
			$success = false;
			foreach($userlist as $u) {
				$user_pass = explode(":",$u);
				if ($user_pass[0] == $user && rtrim($user_pass[1]) == $pass) {
					$success = true;
					break;
				}
			}			

			$msg = '';
			if ($success) {
				echo "<br> Hi $user you have been logged in. <br>";
				$_SESSION['valid'] = true;
				$_SESSION['timeout'] = time();
				$_SESSION['username'] = $user;
				$_SESSION['error'] = "Welcome!";
				header('Location: store.php');
			} else {
				$_SESSION['error']= "Wrong username or password.";
				$_SESSION['valid']=false;
			}	
		?>

		<form class = "form-signin" role = "form"
			action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = post>
			<h4 <?php echo $msg; ?> </h4>
			<input type = "text" name = "username" placeholder = "username" reqiured></br></br>
			<input type = "password" name = "password" placeholder = "password" required> </br></br>
			<button type = "submit" name = "login">Submit</button>
		</form>
		</center>
	</body>
</html>
