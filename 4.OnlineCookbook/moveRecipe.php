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

   $array = json_decode(stripslashes($_POST['data']));
   $menu = $_POST['menu'];

   foreach ($array as $value) {
      $sql = "UPDATE Recipes SET `menu`='$menu' WHERE `title`='$value'";
      $result = mysql_query($sql, $link_db);
      if(mysql_query($sql, $link_db)){
         echo "Updated <br>";
      } else {
         echo "Error, not updated! " . mysql_error();
      } 
   }
?>
