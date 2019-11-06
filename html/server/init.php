<?php
  ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

/************ FUNCTIONS ********************/
  function send_performance_error($con, $file='undefined'){
    $msg = '<meta charset="utf-8"><br>';
    if(mysqli_connect_error()){
      $msg .= mysqli_connect_error();
    }
    if(mysqli_error($con)){
      $msg .= mysqli_error($con);
    }
    $msg .= '<br><br>
      Похоже, что-то пошло не так при работе с сервером баз данных.<br>
      Возможно, один из введенных параметров неверен.<br>
      Пожалуйста, повторите попытку';

    if($file !== 'undefined' && $file !== FALSE) fclose($file);
    if($con) mysqli_close($con);
    die($msg);
  }
/************* MAIN SCOPE *******************/

  $user = trim($_POST['username']);
  $pass = trim($_POST['password']);

  $con = mysqli_connect('127.0.0.1', $user, $pass);
  if(!$con || mysqli_connect_error() || mysqli_error($con)){
      send_performance_error($con);
  }

  $sql = 'CREATE DATABASE admin';
  if(!mysqli_query($con, $sql) || !mysqli_select_db($con, 'admin')){
    send_performance_error($con);
  }

  // data = JSON object, describing data and details
  $sql = array(
    'CREATE TABLE users (username TEXT, password TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',   // users with admin access
    'CREATE TABLE messages (data TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',
    'CREATE TABLE orders (data TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',
    'CREATE TABLE announces (data TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',
  );
  $sql['length'] = count($sql);

  for($i=0; $i != $sql['length']; ++$i){
    if(!mysqli_query($con, $sql[$i])){
      send_performance_error($con);
    }
  }

  $php_defines = '
    <?php
      define("DB_USER", "'.$user.'");
      define("DB_PASSWORD", "'.$pass.'");
      echo DB_USER."  ".DB_PASSWORD;
    ?>
  ';
  $defines_file = fopen('./defines.php', 'w');
  if(!$defines_file){
    send_performance_error($con, $defines_file);
  }
  if(!fwrite($defines_file, $php_defines)){
    send_performance_error($con, $defines_file);
  }
  fclose($defines_file);

  mysqli_close($con);
  die('Панель администратора успешно инициализирована');
?>
