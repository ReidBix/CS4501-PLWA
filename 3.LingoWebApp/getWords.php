<?php
   /*
   Gets the 5 random words for LINGO
   */
   $link_db = new mysqli('localhost', 'mysql_user', 'mysql_pass', 'my_db');
   if ($link_db->connect_error):
      die ("Could not connect to db " . $link_db->connect_error);
   endif;
   $query = "select word from Words order by rand() limit 5";
   $result = $link_db->query($query);
   $rows = $result->num_rows;
   $array = array();
   if ($rows >= 1):
      for ($i=0;$i<5;$i++){
         $row = $result->fetch_array();
            $word = $row[0];
            array_push($array, $row[0]);
      }
      echo json_encode($array);
   else:
      die ("DB Error");
   endif;

   for ($j=0;$j<5;$j++){
      $query2 = "delete from Words where word='$array[$j]'";
      $result = $link_db->query($query2);
   }
?>