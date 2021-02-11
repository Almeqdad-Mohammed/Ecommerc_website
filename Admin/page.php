<?php
  /*
  ==========================================================
  = Manage Members Page
  = You Can Add | Edit |Delete | Members From Here
  ==========================================================
  */
  $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

   // Thepage Is main Page
   if ($do = 'Manage') {
     echo  'Welcome You Are In Manage Categories Page';
     echo "<a href='page.php?do=Add'>Add New Categories</a>";
   }elseif ($do = 'Add') {
     echo  'Welcome You Are In Add Categories Page';
   }elseif ($do = 'Insert') {
     echo  'Welcome You Are In Insert Categories Page';
   }elseif ($do = 'Edit') {
     echo  'Welcome You Are In Manage Categories Page';
   }else {
     echo ' Error There\'s No PAge With Name ';
   }




 ?>
