<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
/********** FUNCTIONS  **********************/
function banner_data($user, $pass){
  if(!R::testConnection()){
    R::setup();
    $banner_left = R::getAll('SELECT * FROM announces WHERE type="left"');
    $banner_right = R::getAll('SELECT * FROM announces WHERE type="right"');
    R::close();
  }
  else {
    $banner_left = R::getAll('SELECT * FROM announces WHERE type="left"');
    $banner_right = R::getAll('SELECT * FROM announces WHERE type="right"');
  }

  if(count($banner_left) > 0){ $banner_left = json_decode($banner_left[0]['data'], true); }
  else{ $banner_left = ['img_src' => '', 'event_type' => '', 'initials' => '', 'event_time' => '', 'event_title' => '', 'event_page' => '']; }

  if(count($banner_right) > 0){ $banner_right = json_decode($banner_right[0]['data'], true); }
  else{ $banner_right = ['img_src' => '', 'event_type' => '', 'initials' => '', 'event_time' => '', 'event_title' => '', 'event_page' => '']; }

  $response = '</div>
    <div class="container py-3 mx-2 border-bottom" id="left_banner_settings">
      <div class="row mx-1"><h4>Настройка левого статического баннера</h4></div>
      <form method="POST" action="/server/admin.php?mode=set_banner&type=left">
      <div class="row mx-1">
        <input type="hidden" name="username" value="'.$user.'">
        <input type="hidden" name="password" value="'.$pass.'">
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="img_src" value="'.$banner_left['img_src'].'" required="" placeholder="Источник изображения"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="event_type" value="'.$banner_left['event_type'].'" required="" placeholder="Тип мероприятия"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="initials" value="'.$banner_left['initials'].'" required="" placeholder="Имя и фамилия"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="event_time" value="'.$banner_left['event_time'].'" required="" placeholder="Время"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="event_title" value="'.$banner_left['event_title'].'" required="" placeholder="Название мероприятия"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="event_page" value="'.$banner_left['event_page'].'" required="" placeholder="Страница мероприятия"> </div>
      </div>
        <div class="row m-1"> <div class="col"> <input type="submit" value="Установить"> </div>  </div>
      </form>
      <form method="POST" action="/server/admin.php?mode=drop_banner&type=left" class="mt-2">
        <input type="hidden" name="username" value="'.$user.'">
        <input type="hidden" name="password" value="'.$pass.'">
        <div class="row mx-1"> <div class="col"> <input type="submit" value="Установить настройки по умолчанию">  </div> </div>
      </form>
    </div>
    <div class="container py-3 mx-2 border-bottom" id="right_banner_settings">
      <div class="row mx-1"><h4>Настройка правого статического баннера</h4></div>
      <form method="POST" action="/server/admin.php?mode=set_banner&type=right">
      <div class="row mx-1">
        <input type="hidden" name="username" value="'.$user.'">
        <input type="hidden" name="password" value="'.$pass.'">
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="img_src" value="'.$banner_right['img_src'].'" required="" placeholder="Источник изображения"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="event_type" value="'.$banner_right['event_type'].'" required="" placeholder="Тип мероприятия"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="initials" value="'.$banner_right['initials'].'" required="" placeholder="Имя и фамилия"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="event_time" value="'.$banner_right['event_time'].'" required="" placeholder="Время"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="event_title" value="'.$banner_right['event_title'].'" required="" placeholder="Название мероприятия"> </div>
        <div class="col col-md-4 col-lg-3 m-1"> <input type="text" name="event_page" value="'.$banner_right['event_page'].'" required="" placeholder="Страница мероприятия"> </div>
      </div>
        <div class="row m-1"> <div class="col"> <input type="submit" value="Установить"> </div>  </div>
      </form>
      <form method="POST" action="/server/admin.php?mode=drop_banner&type=left" class="mt-2">
        <input type="hidden" name="username" value="'.$user.'">
        <input type="hidden" name="password" value="'.$pass.'">
        <div class="row mx-1"> <div class="col"> <input type="submit" value="Установить настройки по умолчанию">  </div> </div>
      </form>
    </div>
  ';

  return $response;
}
/*******************************************/
  define('ADMIN_ERROR_MSG', '<meta charset="utf-8"><pre>Произошла ошибка. Пожалуйста, повторите попытку
  <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
/**********  Modes  ************************/
  if(isset($_GET['mode'])){
    /*********  Show admin panel  ************/
    if($_GET['mode'] == 'login'){

      include_once('./res/defines.php');
      include_once('./res/rb.php');

      $user = trim($_GET['username']);
      $pass = trim($_GET['password']);
      $access_granted = FALSE;

      R::setup( 'mysql:host=localhost;dbname='.DB_NAME, MYSQL_USERNAME, MYSQL_PASSWORD );
      if(!R::testConnection()){
        die(ADMIN_ERROR_MSG);
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
        die(ADMIN_ERROR_MSG);
      }

      $response = '<!DOCTYPE html><html><head>
        <meta charset="utf-8">
        <title>Панель администратора</title>
        <!-- Bootstrap data -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!------------------->
        </head><body>
        <div class="container py-3 mx-2 border-bottom">
          <div class="row mx-1"><h4>Создать пользователя</h4></div>
          <div class="row mx-1"><form method="POST" action="/server/admin.php?mode=create_user">
              <input type="text" name="new_username" pattern="[A-Za-z0-9]{3,25}" title="латинские буквы и цифры, от 3 до 25 символов" required="" placeholder="Имя пользователя">
              <input type="text" name="new_password" pattern="[A-Za-z0-9]{3,25}" title="латинские буквы и цифры, от 3 до 25 символов" required="" placeholder="Пароль">
              <input type="submit" value="Создать">
              <input type="hidden" name="username" value="'.$user.'">
              <input type="hidden" name="password" value="'.$pass.'">
          </form></div>
        </div>
      ';
      $response .= '<div class="container py-3 mx-2 border-bottom"><div class="row mx-1"><h4>Текущие пользователи</h4></div>';
      for($i=0; $i!=$users['count']; ++$i){
        $response .= '<div class="row mx-1"><form method="POST" action="/server/admin.php?mode=delete_user">
        <input type="text" value="'.$users[$i]['username'].'" name="delete_username" readonly>
        <input type="text" value="'.$users[$i]['password'].'" name="delete_password" readonly>
        <input type="hidden" name="username" value="'.$user.'">
        <input type="hidden" name="password" value="'.$pass.'">
        <input type="submit" value="Удалить"></form></div>';
      }

      $response .= banner_data($user, $pass);
      echo $response;

      R::close();
      exit();
    }
    /**************  Create new user  **********/
    if($_GET['mode'] == 'create_user'){

      include_once('./res/defines.php');
      include_once('./res/rb.php');

      $user = $_POST['username'];
      $pass = $_POST['password'];
      $new_user = strtolower(trim($_POST['new_username']));
      $new_pass = strtolower(trim($_POST['new_password']));

      R::setup( 'mysql:host=localhost;dbname='.DB_NAME, MYSQL_USERNAME, MYSQL_PASSWORD );
      if(!R::testConnection()){
        die(ADMIN_ERROR_MSG);
      }

      $users = R::getAll( 'SELECT * FROM users' );
      $users['count'] = count($users);

      $access_granted = FALSE; $user_exists = FALSE;

      for($i=0; $i!=$users['count']; ++$i){
        if($users[$i]['username'] == $user && $users[$i]['password'] == $pass){
          $access_granted = TRUE;
        }
        if($users[$i]['username'] == $new_user){
          $user_exists = TRUE;
        }
      }

      if(
       !$access_granted ||
       $user_exists ||
       strlen($new_user) < 3 ||
       strlen($new_pass) < 3 ||
       strlen($new_pass) > 25 ||
       strlen($new_user) > 25){
        R::close();
        die('<meta charset="utf-8"><pre>
        Похоже, произошла ошибка. Возможные причины:
        <ul><li>У вас недостаточно прав для проведения операции</li>
        <li>Пользователь с таким именем уже существует</li>
        <li>Имя и/или пароль нового пользователя слишком короткий</li>
        <li>Имя и/или пароль нового пользоваьеля слишком длинный</li></ul>
        Пожалуйста, повторите попытку

        <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
      }

      $users = R::dispense('users');
      $users->username = $new_user;
      $users->password = $new_pass;
      R::store($users);
      R::close();

      die('<meta charset="utf-8"><pre>
      Новый пользователь был успешно создан
      <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
    }
    /***************  Delete one of users  ************************/
    if($_GET['mode'] == 'delete_user'){

      include_once('./res/defines.php');
      include_once('./res/rb.php');

      $user = $_POST['username'];
      $pass = $_POST['password'];
      $delete_user = $_POST['delete_username'];
      $delete_pass = $_POST['delete_password'];

      R::setup( 'mysql:host=localhost;dbname='.DB_NAME, MYSQL_USERNAME, MYSQL_PASSWORD );
      if(!R::testConnection()){
        die(ADMIN_ERROR_MSG);
      }

      $users = R::getAll( 'SELECT * FROM users' );
      $users['count'] = count($users);

      if($users['count'] == 1){
        R::close();
        die('<meta charset="utf-8"><pre>
        Единственный пользователь не может быть удален
        <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
      }

      $access_granted = FALSE; $user_exists = FALSE;

      for($i=0; $i!=$users['count']; ++$i){
        if($users[$i]['username'] == $user && $users[$i]['password'] == $pass){
          $access_granted = TRUE;
        }
        if($users[$i]['username'] == $delete_user && $users[$i]['password'] == $delete_pass){
          $user_exists = TRUE;
        }
      }

      if( !$access_granted || !$user_exists ){
        R::close();
        die('<meta charset="utf-8"><pre>
        Похоже, произошла ошибка. Возможные причины:
        <ul><li>У вас недостаточно прав для этой операции</li>
        <li>Пользователь с таким именем не существует</li>
        <li>Имя и/или пароль нового пользователя слишком короткий</li>
        <li>Имя и/или пароль нового пользоваьеля слишком длинный</li></ul>
        Пожалуйста, повторите попытку

        <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
      }

      R::exec('DELETE FROM users WHERE username = "'.$delete_user.'" AND password = "'.$delete_pass.'"');
      R::close();
      die('<meta charset="utf-8"><pre>
      Пользователь был успешно удален!
      <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
    }
    /*************   Set banner settings   *************/
    if($_GET['mode']=='set_banner'){
      include_once('./res/defines.php');
      include_once('./res/rb.php');

      $type = $_GET['type'];
      if($type != 'left' && $type != 'right'){
        die(ADMIN_ERROR_MSG);
      }

      $user = $_POST['username'];
      $pass = $_POST['password'];

      R::setup( 'mysql:host=localhost;dbname='.DB_NAME, MYSQL_USERNAME, MYSQL_PASSWORD );
      if(!R::testConnection()){
        die(ADMIN_ERROR_MSG);
      }

      $users = R::getAll( 'SELECT * FROM users' );
      $users['count'] = count($users);
      $access_granted = FALSE;

      for($i=0; $i!=$users['count']; ++$i){
        if($users[$i]['username'] == $user && $users[$i]['password'] == $pass){
          $access_granted = TRUE;
        }
      }

      if( !$access_granted ){
        R::close();
        die('<meta charset="utf-8"><pre>
        Похоже, произошла ошибка. Возможные причины:
        <ul><li>У вас недостаточно прав для этой операции</li>
        Пожалуйста, повторите попытку

        <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
      }

      $data = [
        'img_src' => $_POST['img_src'],
        'event_type' => $_POST['event_type'],
        'initials' => $_POST['initials'],
        'event_time' => $_POST['event_time'],
        'event_title' => $_POST['event_title'],
        'event_page' => $_POST['event_page'],
      ];

      $data = json_encode($data);

      R::exec('DELETE FROM announces WHERE type="'.$type.'"');
      $announces = R::dispense('announces');
      $announces->type = $type;
      $announces->data = $data;
      R::store($announces);
      R::close();
      die('<meta charset="utf-8"><pre>
      Данные были успешно изменены!
      <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
    }
    /************  Drop banner settings to default  *******/
    if($_GET['mode']=='drop_banner'){
      include_once('./res/defines.php');
      include_once('./res/rb.php');

      $type = $_GET['type'];
      if($type != 'left' && $type != 'right'){
        die(ADMIN_ERROR_MSG);
      }

      $user = $_POST['username'];
      $pass = $_POST['password'];

      R::setup( 'mysql:host=localhost;dbname='.DB_NAME, MYSQL_USERNAME, MYSQL_PASSWORD );
      if(!R::testConnection()){
        die(ADMIN_ERROR_MSG);
      }

      $users = R::getAll( 'SELECT * FROM users' );
      $users['count'] = count($users);
      $access_granted = FALSE;

      for($i=0; $i!=$users['count']; ++$i){
        if($users[$i]['username'] == $user && $users[$i]['password'] == $pass){
          $access_granted = TRUE;
        }
      }

      if( !$access_granted ){
        R::close();
        die('<meta charset="utf-8"><pre>
        Похоже, произошла ошибка. Возможные причины:
        <ul><li>У вас недостаточно прав для этой операции</li>
        Пожалуйста, повторите попытку

        <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
      }

      R::exec('DELETE FROM announces WHERE type="'.$type.'"');

      R::close();

      die('<meta charset="utf-8"><pre>
      Данные были успешно изменены!
      <a href="http://'.$_SERVER['SERVER_NAME'].'/admin">Вернуться</a>');
    }
  }
?>
