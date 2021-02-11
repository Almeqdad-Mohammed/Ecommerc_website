<?php

  function Lang($phrase) {
    static $lang =  array(

      // Homepage
      'MESSAGE' => 'Welcome',
      'ADMIN'   => 'Administrator',
      'SECTIONS'   => 'Categories'

      // Setting
        );
        return $lang[$phrase];
  }
