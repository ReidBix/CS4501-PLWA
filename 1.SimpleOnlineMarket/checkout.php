<?php
	session_start();
	
	if (isset($_SESSION['valid'])){
		if(!$_SESSION['valid']) {
			$_SESSION['error'] = "You don't have access to that site!";
			header('Location: login.php');
		}
	}
	if (isset($_SESSION['username'])){
		$user = $_SESSION['username'];
	}
	if (isset($_SESSION['items'])){
		$items = $_SESSION['items'];
	}
	echo "<center> Welcome to the checkout, $user!";
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if($_POST['logout']){
			unset($_SESSION['error']);
			unset($_SESSION['valid']);
			unset($_SESSION['username']);
			unset($_SESSION['items']);
			header('Location: login.php');
		}
	}	
	$size = count($items);
?>	
	<form acton='checkout.php' method = 'post'>
		<input type='submit' name='logout' value='Logout' />
	</form>
	<table border = "1" width = "60%" height = "40%">
	<h2> Checkout </h2>
	<br/>
<tr align = "center">
	<th>Item</th>
	<th>Price</th>
</tr>
<?php
	$arr = array();
	$fileptr = fopen("store.txt","r");
	if (flock($fileptr, LOCK_SH)) {
		$ctr = 1;
		while ($currline = fgetss($fileptr, 512)){
			$chunks = explode("&", $currline);
			array_push($arr, $chunks);
			$ctr++;
		}
		flock($fileptr, LOCK_UN);
		fclose($fileptr);
	}
	foreach($items as $item) {
		foreach($arr as $i) {
			if($item == $i[0]){
				$cash = $i[2];
?>
	<tr align = "center">
	<td> <?php echo $item;?></td>
	<td> <?php echo $cash;?></td>
	</tr>
<?php
			}
		}
	}
?>
