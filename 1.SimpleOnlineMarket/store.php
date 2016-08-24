<?php
	session_start();
	
	if(isset($_SESSION['items'])){
		$items = $_SESSION['items'];
	} else {
		$items = array();
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if($_POST['AddToCart']){
			$val = $_POST['AddToCart'];
			array_push($items,$val);
			$_SESSION['items'] = $items;
		}
		if(isset($_POST['checkout'])){
			header('Location: checkout.php');
		}
	}

	$size = count($items);
	echo "<div align=right><h2> Number of items in the cart: $size</h2></div>";
	if (isset($_SESSION['valid'])){
		if(!$_SESSION['valid']) {
			$_SESSION['error'] = "You don't have access to that site!";
			header('Location: login.php');
		}
	}
	if (isset($_SESSION['username'])){
		$user = $_SESSION['username'];
	}
	echo "<center> Welcome to the store, $user!";
?>
	<table border = "1" width = "80%" height = "60%">
	<caption><h2> Online Marketplace </h2>
	<b><i> Select an item and add it to the cart </i></b>
	<br/><br/>
	</caption>
<tr align = "center">
	<th>Item</th>
	<th>Image</th>
	<th>Price</th>
	<th>Add to Cart</th>
</tr>
<?php
	$fileptr = fopen("store.txt", "r");
	if (flock($fileptr, LOCK_SH)) {
		$ctr = 1;
		while ($currline = fgetss($fileptr, 512)){
			$chunks = explode("&", $currline);
?>
	<tr align = "center">
	<td> <?php echo $chunks[0];?>
	</td>
	<td> <?php echo $chunks[1];?> </td>
	<td> <?php echo $chunks[2];?> </td>
	 <td> <form action='' method='post'>
		<button name = 'AddToCart' value = '<?php echo $chunks[0]?>'>Add To Cart</button>
		</form>
	
	</td>
	</tr>
<?php
			$ctr++;
		}
		flock($fileptr, LOCK_UN);
		fclose($fileptr);
	}
?>
	<tr align='center'> 
	<td> </td>
	<td> </td>
	<td> </td>	
	<td> <form acton='checkout.php' method = 'post'>
		<input type='submit' name='checkout' value='Checkout' />
		</form>
	 </td>
	</tr>
<?php
	echo "</center>";
?>
