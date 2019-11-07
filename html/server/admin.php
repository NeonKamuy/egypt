<?php
  /**********   FUNCTIONS   ***********/
  function get_html_parts($user, $pass){
    return array(
      'header' => '<!DOCTYPE html><html><head>
        <meta charset="utf-8">
        <title>Панель администратора</title>
        <!-- Bootstrap data -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!------------------->
        </head><body>
        <div class="container py-3 mx-2 border-bottom">
          <h4>Создать пользователя</h4>
          <div class="row"> <form method="POST" action="/server/admin.php?mode=create_user">
              <input type="text" name="new_username" pattern="[A-Za-z0-9]{3,25}" title="латинские буквы и цифры, от 3 до 25 символов" required="" placeholder="Имя пользователя">
              <input type="text" name="new_password" pattern="[A-Za-z0-9]{3,25}" title="латинские буквы и цифры, от 3 до 25 символов" required="" placeholder="Пароль">
              <input type="submit" value="Создать">
              <input type="hidden" name="username" value="'.$user.'">
              <input type="hidden" name="password" value="'.$pass.'">
          </form> </div>
        </div>
      ',

      'announce_left' => '
        <div class="container py-3 mx-2 border-bottom" id="left_banner_settings">
          <h4>Настройка левого статического баннера</h4>
          <form method="POST" action="/server/admin.php?mode=set_banner&type=left">
            <input type="hidden" name="username" value="'.$user.'">
            <input type="hidden" name="password" value="'.$pass.'">
            <input type="text" name="img_src" required="" placeholder="Источник изображения">
            <input type="text" name="event_type" required="" placeholder="Тип мероприятия">
            <input type="text" name="initials" required="" placeholder="Имя и фамилия">
            <input type="text" name="event_time" required="" placeholder="Время">
            <input type="text" name="event_title" required="" placeholder="Название мероприятия">
            <input type="text" name="event_page" required="" placeholder="Страница мероприятия">
            <input type="submit" value="Установить"></form>
          <form method="POST" action="/server/admin.php?mode=drop_banner&type=left">
            <input type="hidden" name="username" value="'.$user.'">
            <input type="hidden" name="password" value="'.$pass.'">
            <input type="submit" value="Установить настройки по умолчанию"></form>
        </div>
      ',

      'announce_right' => '
        <div class="container py-3 mx-2 border-bottom" id="right_banner_settings">
          <h4>Настройка правого статического баннера</h4>
          <form method="POST" action="/server/admin.php?mode=set_banner&type=right">
            <input type="hidden" name="username" value="'.$user.'">
            <input type="hidden" name="password" value="'.$pass.'">
            <input type="text" name="img_src" required="" placeholder="Источник изображения">
            <input type="text" name="event_type" required="" placeholder="Тип мероприятия">
            <input type="text" name="initials" required="" placeholder="Имя и фамилия">
            <input type="text" name="event_time" required="" placeholder="Время">
            <input type="text" name="event_title" required="" placeholder="Название мероприятия">
            <input type="text" name="event_page" required="" placeholder="Страница мероприятия">
            <input type="submit" value="Установить">
          <form method="POST" action="/server/admin.php?mode=drop_banner&type=left">
            <input type="hidden" name="username" value="'.$user.'">
            <input type="hidden" name="password" value="'.$pass.'">
            <input type="submit" value="Установить настройки по умолчанию"></form>
          </form>
        </div>
      ',
    );
  }


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
      $html = get_html_parts($user, $pass);

      $response = $html['header'];
      $response .= '<div class="container py-3 mx-2 border-bottom"><h4>Текущие пользователи</h4>';
      for($i=0; $i!=$users['count']; ++$i){
        $response .= '<div class="row"><form method="POST" action="/server/admin.php?mode=delete_user">
        <input type="text" value="'.$users[$i]['username'].'" name="delete_username" readonly>
        <input type="text" value="'.$users[$i]['password'].'" name="delete_password" readonly>
        <input type="hidden" name="username" value="'.$user.'">
        <input type="hidden" name="password" value="'.$pass.'">
        <input type="submit" value="Удалить"></form></div>';
      }
      $response .= '</div>'.$html['announce_left'].$html['announce_right'];
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
        <li>Пользователь с таким именем не существует</li>
        <li>Имя и/или пароль нового пользователя слишком короткий</li>
        <li>Имя и/или пароль нового пользоваьеля слишком длинный</li></ul>
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
        <li>Пользователь с таким именем не существует</li>
        <li>Имя и/или пароль нового пользователя слишком короткий</li>
        <li>Имя и/или пароль нового пользоваьеля слишком длинный</li></ul>
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
