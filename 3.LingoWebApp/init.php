<script language="javascript" type="text/javascript">
sessionStorage.clear();
localStorage.clear();
</script>

<?php
	session_start();
	if(isset($_SESSION['init'])){
		$init = $_SESSION['init'];
	} else {
		$init = 0;
	}
	if(isset($_SESSION['cleared'])){
		$cleared = 1;
		unset($_SESSION['cleared']);
	} else {
		$cleared = 0;
	}

	$link_db = mysql_connect('localhost', 'mysql_user', 'mysql_pass', 'my_db');
	if (!$link_db){
		die('Could not connect: ' . mysql_error());
	}

	if($init == 0){ 
		if(isset($_POST['create'])){
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
			$sql = "CREATE TABLE Words (
			Word VARCHAR(5) NOT NULL,
			ID INT(10) NOT NULL
			)";
			if(mysql_query($sql, $link_db)){
				//echo "Table Words created<br>";
			} else {
				echo "Error, table Words not created! " . mysql_error();
			}
			$wordlist = file('words5.txt');	
			$ctr = 1;
			foreach($wordlist as $w) {
				$word = $w;
				$sql = "INSERT INTO `Words` (`Word`, `ID`) VALUES('$word', '$ctr')";
				if(mysql_query($sql, $link_db)){
					//echo "Words added to<br>";
				} else {
					echo "Error, Words not added to! " . mysql_error();
				}
				$ctr = $ctr + 1;
			}		
			$_SESSION['msg'] = "Database created";
			$_SESSION['init'] = 1;
		}
		if(isset($_POST['clear'])){
			if (!$cleared) {
			$_SESSION['msg'] = "Database has not yet been created!";
			}
		}
	} else {
		if(isset($_POST['create'])){
			$_SESSION['msg'] = "Database has already been created!";
		}
		if (isset($_POST['clear'])){
			setcookie("cookie", "", time()-3600);
			$sql = 'DROP DATABASE my_db';
			if(mysql_query($sql, $link_db)){
				//echo "Dropped!";
			} else{
				echo "Error, not dropped! " . mysql_error();
			}
			session_unset();
			$_SESSION['msg'] = "Database has been cleared!";
			$_SESSION['cleared'] = 1;
			echo "<script>parent.window.location.reload(true);</script>";
			die();
		}
	}
	echo "<center>";
	if(isset($_SESSION['msg'])){
		$msg = $_SESSION['msg'];
		echo $msg;	
		$_SESSION['msg'] = "Welcome to init.php!";
	} else { 
		echo "Welcome to init.php gayboi!";
	}	
	echo "</center>";	
?> 

<html lang = "en">
	<head>
		<title> Initialization Script </title>
	</head>

	<body>
		<br>
		<center>
	<form acton='init.php' method = 'post'>
		<input type='submit' name='create' value='Create Database' />
	</form>
	<form acton='init.php' method = 'post'>
		<input type='submit' name='clear' value='Clear Database' />
	</form>
			<a href="start.php">Login</a>
		</center>
	</body>
</html>
