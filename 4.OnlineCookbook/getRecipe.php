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

   $title = $_POST['title'];

   $sql = "SELECT * FROM Recipes WHERE `title`='$title'";
   $result = mysql_query($sql, $link_db);
   $results = array();
   while ($row = mysql_fetch_array($result)) {
      $results[] = $row['title'];
      $results[] = $row['ingredients'];
      $results[] = $row['directions'];
      $results[] = $row['rating'];
      $results[] = $row['menu'];
   }
   $json = json_encode($results);
   echo $json;
?>
