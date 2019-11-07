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
    mysqli_query($con, 'DROP DATABASE '.DB_NAME);
    $error_message = mysqli_error($con);
    mysqli_close($con);
    return $error_message;
  }
/************* MAIN SCOPE *******************/

  if(!$_POST['username'] || !$_POST['password']){
    die('  <html dir="ltr" lang="en"><head>
        <meta charset="utf-8">
        <title>Панель администратора</title>
        <style>
          h3,span,form > input { margin: 10px 5px; }
        </style>
      </head>
      <body>
        <h3> Инициализации панели администратора </h3>
        <span> Введите имя и пароль пользователя БД, чтобы продолжить </span>
        <form method="POST" action="/server/init.php">
          <input type="text" placeholder="Логин" name="username" pattern="[A-Za-z0-9]{1,}" title="латинские буквы и цифры" required="">
          <input type="password" placeholder="Пароль" name="password" pattern="[A-Za-z0-9]{1,}" title="латинские буквы и цифры" required="">
          <input type="submit" value="Войти">
        </form>

      </body></html>');
  }

  $user = trim($_POST['username']);
  $pass = trim($_POST['password']);

  $con = mysqli_connect('127.0.0.1', $user, $pass);
  if(!$con || mysqli_connect_error() || mysqli_error($con)){
      $error_message = mysqli_error($con);
      send_performance_error($error_message);
      mysqli_close($con);
  }

  $sql = 'CREATE DATABASE '.DB_NAME;
  if(!mysqli_query($con, $sql) || !mysqli_select_db($con, DB_NAME)){
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
      define("MYSQL_USERNAME", "'.$user.'");
      define("MYSQL_PASSWORD", "'.$pass.'");
      define("DB_NAME", "'.DB_NAME.'");
      define("ADMIN_ERROR_MSG", "<meta charset=\"utf-8\"><pre>Произошла ошибка. Пожалуйста, повторите попытку
      <a href=\"http://'.$_SERVER['SERVER_NAME'].'/admin\">Вернуться</a>");
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
