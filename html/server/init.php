<?php
  ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

  include('./res/init_resources.php');    // getting text data and large variables

/************ FUNCTIONS ********************/
  function send_performance_error($message=FALSE){
    $msg = PERFORMANCE_ERROR_MESSAGE_TEXT;
    if($message) $msg .= $message;
    die($msg);
  }

  function drop_db_onerror($con){
    mysqli_query($con, 'DROP DATABASE admin');
    $error_message = mysqli_error($con);
    mysqli_close($con);
    return $error_message;
  }
/************* MAIN SCOPE *******************/

  $user = trim($_POST['username']);
  $pass = trim($_POST['password']);

  $con = mysqli_connect('127.0.0.1', $user, $pass);
  if(!$con || mysqli_connect_error() || mysqli_error($con)){
      $error_message = mysqli_error($con);
      send_performance_error($error_message);
      mysqli_close($con);
  }

  $sql = 'CREATE DATABASE admin';
  if(!mysqli_query($con, $sql) || !mysqli_select_db($con, 'admin')){
    $error_message = mysqli_error($con);
    mysqli_close($con);
    send_performance_error($error_message);
  }

  for($i=0; $i != $create_table_queries['length']; ++$i){
    if(!mysqli_query($con, $create_table_queries[$i])){
      $error_message = drop_db_onerror($con);
      send_performance_error($error_message);
    }
  }

  $php_defines = '<?php
      define("DB_USER", "'.$user.'");
      define("DB_PASSWORD", "'.$pass.'");
      define("DB_NAME", "'.DB_NAME.'");
    ?>';
  $defines_file = fopen('./res/defines.php', 'w');
  if(!$defines_file){
    $error_message = drop_db_onerror($con);
    send_performance_error($error_message);
  }
  if(!fwrite($defines_file, $php_defines)){
    $error_message = drop_db_onerror($con);
    fclose($defines_file);
    send_performance_error($error_message);
  }
  fclose($defines_file);

  $admin_login_page = fopen('../admin/index.html', 'w');
  if(!$admin_login_page){
    $error_message = drop_db_onerror($con);
    send_performance_error($error_message);
  }

  if(!fwrite($admin_login_page, ADMIN_LOGIN_PAGE_innerHTML)){
    $error_message = drop_db_onerror($con);
    fclose($admin_login_page);
    send_performance_error($error_message);
  }
  fclose($admin_login_page);

  die(SUCCESS_END_TEXT);
?>
