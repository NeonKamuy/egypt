<?php
  ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

/************ FUNCTIONS ********************/
  function send_performance_error($message=FALSE){
    $msg = '<meta charset="utf-8"><pre style="font-size: 0.9rem">
Похоже, что-то пошло не так во время работы скрипта.
Некоторые возможные ошибки:<ul>
<li>Один или все введенныe параметры неверен</li>
<li>Не удалось подключиться к серверу баз данных</li>
<li>База данных "admin" уже существует, либо произошел сбой во время работы с ней</li>
<li>Недостаточно прав для записи в директорию /server или /admin</li></ul>
Пожалуйста, повторите попытку.

  </pre>';

    if($message) $msg .= $message;
    die($msg);
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

  // data = JSON object, describing data and details
  $sql = array(
    'CREATE TABLE users (username TEXT, password TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',   // users with admin access
    'CREATE TABLE messages (data TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',
    'CREATE TABLE orders (data TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',
    'CREATE TABLE announces (data TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',
    'INSERT INTO users (username, password) VALUES (\'admin\', \'admin\')',
  );
  $sql['length'] = count($sql);

  for($i=0; $i != $sql['length']; ++$i){
    if(!mysqli_query($con, $sql[$i])){
      mysqli_query($con, 'DROP DATABASE admin');
      $error_message = mysqli_error($con);
      mysqli_close($con);
      send_performance_error($error_message);
    }
  }

  $php_defines = '
    <?php
      define("DB_USER", "'.$user.'");
      define("DB_PASSWORD", "'.$pass.'");
      echo DB_USER."  ".DB_PASSWORD;
    ?>
  ';
  $defines_file = fopen('./res/defines.php', 'w');
  if(!$defines_file){
    mysqli_query($con, 'DROP DATABASE admin');
    $error_message = mysqli_error($con);
    mysqli_close($con);
    send_performance_error($error_message);
  }
  if(!fwrite($defines_file, $php_defines)){
    mysqli_query($con, 'DROP DATABASE admin');
    $error_message = mysqli_error($con);
    mysqli_close($con);
    fclose($defines_file);
    send_performance_error($error_message);
  }
  fclose($defines_file);

  $admin_login_page = fopen('../admin/index.html', 'w');
  if(!$admin_login_page){
    mysqli_query($con, 'DROP DATABASE admin');
    $error_message = mysqli_error($con);
    mysqli_close($con);
    send_performance_error($error_message);
  }

  $admin_login_page_innerHTML = '
  <!DOCTYPE html>
  <html dir="ltr" lang="en"><head>
  <meta charset="utf-8">
  <title>Панель администратора</title>
  <style>
    h3,span,form > input { margin: 10px 5px; }
  </style></head>
  <body>
  <h3> Панель администратора </h3>
  <span> Войдите в учетную запись, чтобы продолжить </span>
  <form method="POST" action="/server/admin.php?login">
    <input type="text" placeholder="Логин" name="username" pattern="[A-Za-z0-9]+" title="латинские буквы и цифры" required="">
    <input type="password" placeholder="Пароль" name="password" pattern="[A-Za-z0-9]+" title="латинские буквы и цифры" required="">
    <input type="submit" value="Войти">
  </form> </body></html>
  ';

  if(!fwrite($admin_login_page, $admin_login_page_innerHTML)){
    mysqli_query($con, 'DROP DATABASE admin');
    $error_message = mysqli_error($con);
    mysqli_close($con);
    fclose($admin_login_page);
    send_performance_error($error_message);
  }
  fclose($admin_login_page);

  die('<meta charset="utf-8"><pre style="font-size: 0.9rem">
  Панель администратора успешно инициализирована.
  Войти можно по адресу '.$_SERVER['SERVER_NAME'].'/admin
  Логин: admin
  Пароль: admin
  Сменить логин и пароль можно в панели администратора');
?>
