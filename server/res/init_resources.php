<?php
  define('DB_NAME', 'admin_'.mt_rand());

  define('PERFORMANCE_ERROR_MESSAGE_TEXT', '<meta charset="utf-8"><pre style="font-size: 0.9rem">
    Похоже, что-то пошло не так во время работы скрипта.
    Некоторые возможные ошибки:<ul>
    <li>Один или все введенныe параметры неверен</li>
    <li>Не удалось подключиться к серверу баз данных</li>
    <li>База данных "'.DB_NAME.'" уже существует, либо произошел сбой во время работы с ней</li>
    <li>Недостаточно прав для записи в директорию /server или /admin</li></ul>
    Пожалуйста, повторите попытку.

    </pre>');

  define('SUCCESS_END_TEXT', '<meta charset="utf-8"><pre style="font-size: 0.9rem">
  Создана база данных "'.DB_NAME.'".
  Панель администратора успешно инициализирована.
  Войти можно по адресу '.$_SERVER['SERVER_NAME'].'/admin
  Логин: admin
  Пароль: admin
  Сменить логин и пароль можно в панели администратора

  <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Войти</a>');

  define('ADMIN_LOGIN_PAGE_innerHTML', '
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
  <form method="GET" action="/server/admin.php">
    <input type="text" placeholder="Логин" name="username" pattern="[A-Za-z0-9]{3,25}" title="латинские буквы и цифры, от 3 до 25 символов" required="">
    <input type="password" placeholder="Пароль" name="password" pattern="[A-Za-z0-9]{3,25}" title="латинские буквы и цифры, от 3 до 25 символов" required="">
    <input type="hidden" name="mode" value="login">
    <input type="submit" value="Войти">
  </form> </body></html>

  <!--
  <html dir="ltr" lang="en"><head>
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

  </body></html>
  -->
  ');

  // data = JSON object, describing data and details
  $create_table_queries = array(
    'CREATE TABLE users (username TEXT, password TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',   // users with admin access
    'CREATE TABLE messages (data TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',
    'CREATE TABLE orders (data TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',
    'CREATE TABLE announces (type TEXT, data TEXT, id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))',
    'INSERT INTO users (username, password) VALUES (\'admin\', \'admin\')',
  );
  $create_table_queries['length'] = count($create_table_queries);

?>
