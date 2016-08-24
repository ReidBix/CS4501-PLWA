<?php
   session_start();
   $first = 0;
   if (!isset($_SESSION['first'])){
      $_SESSION['first'] = 0;
      $first = $_SESSION['first'];
   } else {
      $first = $_SESSION['first'];
   }

   if ($first < 6) {
      $link_db = mysql_connect('localhost', 'mysql_user', 'mysql_pass', 'my_db');
      if (!$link_db){
         die('Could not connect: ' . mysql_error());
      }

      $sql = "USE my_db";  
      if(mysql_query($sql, $link_db)){
         echo "Using database<br>";
      } else {
         echo "Error, not using! " . mysql_error();
      }  

      $title = $_POST['title'];
      $ingredients = $_POST['ingredients'];
      $directions = $_POST['directions'];
      $rating = $_POST['rating'];
      $menu = $_POST['menu'];
      $sql = "INSERT INTO `Recipes` (`title`, `ingredients`, `directions`, `rating`, `menu`) VALUES('$title', '$ingredients', '$directions', '$rating', '$menu')";
      if(mysql_query($sql, $link_db)){
         echo "Recipes added to<br>";
      } else {
         echo "Error, Recipes not added to! " . mysql_error();
      }
      $_SESSION['first'] = $first + 1;
   } else {
      echo "set DONE";
   }
   
?>
