<?php
   session_start();
   $first = 0;
   if (!isset($_SESSION['first'])){
      $_SESSION['first'] = 0;
   } else {
      $first = $_SESSION['first'];
      $_SESSION['first'] = 6;
   }

   if ($first < 6) {
      $link_db = mysql_connect('localhost', 'mysql_user', 'mysql_pass', 'my_db');
      if (!$link_db){
         die('Could not connect: ' . mysql_error());
      }

      $sql = "CREATE DATABASE IF NOT EXISTS my_db";
      if(mysql_query($sql, $link_db)){
          //echo "Created<br>";
      } else {
         echo "Error, not created! " . mysql_error();
      }

      $sql = "USE my_db";  
      if(mysql_query($sql, $link_db)){
         echo "Using<br>";
      } else {
         echo "Error, not using! " . mysql_error();
      }   

      $sql = "DROP TABLE IF EXISTS Recipes"; 
      if(mysql_query($sql, $link_db)){
         echo "Table Recipes dropped<br>";
      } else {
         echo "Error, table Recipes not dropped! " . mysql_error();
      }

      $sql = "CREATE TABLE IF NOT EXISTS Recipes (
      title VARCHAR(10000) NOT NULL,
      ingredients VARCHAR(10000) NOT NULL,
      directions VARCHAR(10000) NOT NULL,
      rating VARCHAR(10000) NOT NULL,
      menu VARCHAR(10000) NOT NULL
      )"; 
      if(mysql_query($sql, $link_db)){
         echo "Table Recipes created<br>";
      } else {
         echo "Error, table Recipes not created! " . mysql_error();
      }
   } else {
      echo "get DONE";
   }
   

?>
