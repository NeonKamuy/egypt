<?php
  if(isset($_GET['mode']) && $_GET['mode'] == 'login'){
    /******************************/
    include_once('./res/defines.php');
    include_once('./res/rb.php');
    /******************************/
    $user = $_GET['username'];
    $pass = $_GET['password'];

    R::setup( 'mysql:host=localhost;dbname='.DB_NAME, DB_USER, DB_PASSWORD );
  }
?>
