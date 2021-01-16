<?php

  include 'connect.php';

  //Routes
  $tp1     = 'includes/templates/';    // templates directory
  $lang    = 'includes/languages/';    // language directory
  $func    = 'includes/functions/';    // functions directory
  $css     = 'layout/css/';			       // css directory
  $js      = 'layout/js/';			       // js directory
  $avatars = 'admin/uploads/avatars/';  // avatar directory
 

 

  // include important files

  include $func . 'functions.php';
  include $lang . 'english.php';
  include $tp1  . 'header.php';

  if (!isset($noNavbar)) {include $tp1  . 'navbar.php';}
  
	