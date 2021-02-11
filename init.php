<?php

    include 'Admin/connect.php';

    $sessionUser = '';
    if (isset($_SESSION['user'])) {
      $sessionUser = $_SESSION['user'];
    }
    // Routes

    $tpl  = 'includes/templates/'; // Templatee Directory
    $css  = 'Layout/css/'; // Css Directory
    $js   = 'Layout/Js/'; // Js Directory
    $lang = 'includes/Languages/'; // Lanuages Directory
    $func = 'includes/Functions/'; // Function Directory

    // includes Important Files

    include $func . 'function.php';
    include $lang . 'English.php';
    include $tpl . 'header.php';

    // Includes Vavbar On All Pages Ecpect The One With NoNavbar Variable

    //if (!isset($noNavbar)) {include $tpl . 'Navbar.php';}
