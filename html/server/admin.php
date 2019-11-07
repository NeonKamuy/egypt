<?php
  if(isset($_GET['mode']) && $_GET['mode'] == 'login'){
    /******************************/
    include_once('./res/defines.php');
    include_once('./res/rb.php');
    /******************************/
    $user = trim($_GET['username']);
    $pass = trim($_GET['password']);
    $access_granted = FALSE;

    R::setup( 'mysql:host=localhost;dbname='.DB_NAME, MYSQL_USERNAME, MYSQL_PASSWORD );
    if(!R::testConnection()){
      die('<meta charset="utf-8">Не удалось подключиться к базе данных. Пожалуйста, повторите попытку');
    }

    $users = R::getAll( 'SELECT * FROM users' );
    $users['count'] = count($users);

    for($i=0; $i!=$users['count']; ++$i){
      if($users[$i]['username'] == $user && $users[$i]['password'] == $pass){
        $access_granted = TRUE;
        break;
      }
    }
    if(!$access_granted){
      die('<meta charset="utf-8">Введенный логин и/или пароль неверен. Пожалуйста, повторите попытку');
    }

    var_dump($users);
    exit();
  }
?>
