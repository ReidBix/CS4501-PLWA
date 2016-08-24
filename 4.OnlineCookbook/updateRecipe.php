<?php
   	$link_db = mysql_connect('localhost', 'mysql_user', 'mysql_pass', 'my_db');
   	if (!$link_db){
      die('Could not connect: ' . mysql_error());
   	}

   	$sql = "USE my_db";  
   	if(mysql_query($sql, $link_db)){
   	   //echo "Using database<br>";
   	} else {
   	   echo "Error, not using! " . mysql_error();
   	} 

	$lastTitle = $_POST['lastTitle'];
	$title = $_POST['title'];
	$rating = $_POST['rating'];
	$directions = $_POST['directions'];
	$ingredients = $_POST['ingredients'];
	$menu = $_POST['menu'];

	if ($lastTitle != "") {
		$sql = "UPDATE Recipes SET `title`='$title', `rating`='$rating', `directions`='$directions', `ingredients`='$ingredients' WHERE `title`='$lastTitle'";
	    $result = mysql_query($sql, $link_db);
	    if(mysql_query($sql, $link_db)){
	    	echo "Saved <br>";
	    } else {
	    	echo "Error, not saved! " . mysql_error();
	    } 
	} else {
		$sql = "INSERT INTO `Recipes` (`title`, `ingredients`, `directions`, `rating`, `menu`) VALUES('$title', '$ingredients', '$directions', '$rating', '$menu')";
	   if(mysql_query($sql, $link_db)){
	      echo "Recipes added to<br>";
	   } else {
	      echo "Error, Recipes not added to! " . mysql_error();
	   }
	}

    
?>
