<?php

  // Connection To Database Wit PDO

  $dns = 'mysql:host=localhost;dbname=shop';
  $user = 'root';
  $passwd = '';
  $optino =  array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', );

    try {
      $con = new PDO( $dns , $user , $passwd , $optino);
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //echo  "Welcome You Are Connect To Database";
    } catch (PDOException $e) {
      echo "Failed To Connect " . $e->getMessage();
    }
