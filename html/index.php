<!DOCTYPE html><html lang="en"><head>

  <?php
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

    if(file_exists('./server/res/defines.php')){
      include_once('./server/res/defines.php');

      include_once('./server/res/rb.php');

      R::setup( 'mysql:host=localhost;dbname='.DB_NAME, MYSQL_USERNAME, MYSQL_PASSWORD );

      if(R::testConnection()){
        $announces = R::getAll( 'SELECT * FROM announces' );
        $announces['count'] = count($announces);
        $announces['carousel'] = [];

        for($i=0; $i!=$announces['count']; ++$i){
          if($announces[$i]['type'] == 'left'){
            $announces['left'] = $announces[$i]['data'];
          }
          else if($announces[$i]['type'] == 'right'){
            $announces['right'] = $announces[$i]['data'];
          }
          else if($announces[$i]['type'] == 'carousel'){
            $announces['carousel'] = $announces[$i]['data'];
          }
        }

        R::close();

        if(isset($announces['left'])) $left_announce = json_decode($announces['left'], TRUE);
        if(isset($announces['right'])) $right_announce = json_decode($announces['right'], TRUE);
      }

      else echo '<script>console.log("Не удалось подключиться к базе данных");</script>';
    }
  ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="/img/nav-icon.png">

  <title>Храмовый комплекс Тота-Маат</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:700">

  <!-- Custom styles for this template -->
  <link href="css/agency.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Navigation -->
  <a id="pyramid_button_mob" onclick="show_navbar('pyramid')"> <img src="/img/nav-icon.png" width="30" height="30" > </a>   <!-- навигационная кнопка пирамидки в -sm -->

  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <a id="navigation_button_mob" onclick="show_navbar('main')"> <i class="fas fa-bars"></i> </a>    <!-- навигационная кнопка основной панели в -sm  -->
    <a class="navbar-brand navbar-brand-own" href="#" id='navbar_brand_own_sm'>Храмовый комплекс Тота-Маат</a>
    <a class="navbar-brand navbar-brand-own" href="#" id='navbar_brand_own_xs'>Храмовый комплекс</a>

    <div class="container" id="navbarResponsiveContainer">
      <div class="collapse navbar-collapse" id="pyramid_navbar_mob">
        <ul class="navbar-nav text-uppercase ml-auto">
          <li class="nav-item">
            <a class="nav-link">История создания комплекса</a>
          </li>
          <li class="nav-item">
            <a class="nav-link dropdown-toggle" onclick="add_elements(event)">Эзотуризм</a>
            <a class="nav-link pyramid_nav_subdropdown">Ближайшие путешествия</a>
            <a class="nav-link pyramid_nav_subdropdown">Отчеты о прошедших путешествиях</a>
          </li>
          <li class="nav-item">
            <a class="nav-link">Артефакты</a>
          </li>
          <li class="nav-item">
            <a class="nav-link">Статьи</a>
          </li>
        </ul>
      </div>

      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav text-uppercase ml-auto">
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#complex-struct">Структура комплекса</a>
          </li>

          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#learning-et-services">Обучение и услуги</a>
          </li>

          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#responses">Отзывы о работе в комплексе</a>
          </li>

          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#contact">Контакты</a>
          </li>

          <li class="nav-item dropdown" id="pyramid_button">
            <a class="navbar-brand ml-md-5" data-toggle="dropdown">
              <img src="/img/nav-icon.png" width="30" height="30" class="d-inline-block align-top" alt="">
            </a>
            <div class="dropdown-menu" style="margin-left: -240px; overflow: hidden" aria-labelledby="navbarDropdownMenuLink">
             <a class="dropdown-item" href="#">История создания комплекса</a>
             <a class="dropdown-item dropdown-toggle" onclick="add_elements(event)">Эзотуризм</a>
             <a class="dropdown-item pyramid_nav_subdropdown">Ближайшие путешествия</a>
             <a class="dropdown-item pyramid_nav_subdropdown">Отчеты о прошедших путешествиях</a>
             <a class="dropdown-item" href="#">Артефакты</a>
             <a class="dropdown-item" href="#">Статьи</a>
           </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Header -->
  <header class="masthead">
    <div class="container">
      <div class="intro-text">
        <div class="intro-lead-in">Храмовый комплекс Тота-Маат</div>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
    </div>
  </header>

  <!-- Блок анонсов -->
  <section class="pt-5 pb-0 mb-n2">
    <div class="container-fluid" id="announces">
      <div class="row text-center" id="announces_row">
        <div class="col md-4 mx-2">
          <?php
            if(isset($left_announce)){
              echo '
              <div class="static_announce_wrap" style="background-image:url(\''.$left_announce['img_src'].'\')">
                <div class="announce_annotation">
                  <div class="annotation_description">
                    '.$left_announce['event_type'].'
                    '.$left_announce['initials'].'
                  </div>
                  <div class="annotation_time">
                    '.$left_announce['event_time'].'
                  </div>
                  <div class="annotation_title">
                    '.$left_announce['event_title'].'
                  </div>
                  <a class="annotation_button" href="'.$left_announce['event_page'].'">
              ';
            }
            else echo '
              <script>console.log("Нет данных по левому статическому баннеру");</script>
              <div class="static_announce_wrap" style="background-image:url(\'/img/announces_static/banner1.jpg\')">
                <div class="announce_annotation">
                  <div class="annotation_description">
                    Тип мероприятия
                    Имя Фамилия
                  </div>
                  <div class="annotation_time">
                    6 июня, 2026
                  </div>
                  <div class="annotation_title">
                    Название мероприятия
                  </div>
                  <a class="annotation_button">
                ';
                                    ?>
                Подробнее
              </a>
            </div>
          </div>
        </div>

        <div class="col md-4 mx-2" id="main_carousel">
          <div class="carousel_announce_wrap" style="background-image:url('/img/announces_carousel/banner1.jpg'); display: block">   <!-- Установить display:block только для первого баннера карусели, чтобы они не накладывались до обработки скриптом  -->
            <div class="announce_annotation">
              <div class="annotation_description">
                Тип мероприятия
                Имя Фамилия
              </div>
              <div class="annotation_time">
                26 мая, 2028
              </div>
              <div class="annotation_title">
                Название мероприятия
              </div>
              <a class="annotation_button">
                Подробнее
              </a>
            </div>
          </div>

          <div class="carousel_announce_wrap" style="background-image:url('/img/announces_carousel/banner2.jpg')">
            <div class="announce_annotation">
              <div class="annotation_description">
                Тип мероприятия
                Имя Фамилия
              </div>
              <div class="annotation_time">
                26 мая, 2028
              </div>
              <div class="annotation_title">
                Название мероприятия
              </div>
              <a class="annotation_button">
                Подробнее
              </a>
            </div>
          </div>

          <div class="carousel_announce_wrap" style="background-image:url('/img/announces_carousel/banner3.jpg')">
            <div class="announce_annotation">
              <div class="annotation_description">
                Тип мероприятия
                Имя Фамилия
              </div>
              <div class="annotation_time">
                26 мая, 2028
              </div>
              <div class="annotation_title">
                Название мероприятия
              </div>
              <a class="annotation_button">
                Подробнее
              </a>
            </div>
          </div>
        </div>

        <div class="col md-4 mx-2">
          <?php

            if(isset($right_announce)){
              echo '
              <div class="static_announce_wrap" style="background-image:url(\''.$right_announce['img_src'].'\')">
                <div class="announce_annotation">
                  <div class="annotation_description">
                    '.$right_announce['event_type'].'
                    '.$right_announce['initials'].'
                  </div>
                  <div class="annotation_time">
                    '.$right_announce['event_time'].'
                  </div>
                  <div class="annotation_title">
                    '.$right_announce['event_title'].'
                  </div>
                  <a class="annotation_button" href="'.$right_announce['event_page'].'">
              ';
            }
            else echo '
            <script>console.log("Нет данных по правому статическому баннеру");</script>
            <div class="static_announce_wrap" style="background-image:url(\'/img/announces_static/banner2.jpg\')">
              <div class="announce_annotation">
                <div class="annotation_description">
                  Тип мероприятия
                  Имя Фамилия
                </div>
                <div class="annotation_time">
                  14 августа, 2028
                </div>
                <div class="annotation_title">
                  Название мероприятия
                </div>
                <a class="annotation_button">
                ';
                                    ?>
                Подробнее
              </a>
            </div>
          </div>
        </div>
    </div>
  </section>

  <!-- Структура комплекса -->
  <section class="page-section" id="complex-struct">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase"><img class='horus_eye_ico'>Структура комплекса</h2>
          <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
        </div>
      </div>
      <div class="row text-center">
        <div class="col-md-4">
          <a href="#" class="heading_alias"><h4 class="service-heading">Пирамида Тота-Маат</h4></a>
          <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
        </div>
        <div class="col-md-4">
          <a href="#" class="heading_alias"><h4 class="service-heading">Храм Исиды</h4></a>
          <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
        </div>
        <div class="col-md-4">
          <a href="#" class="heading_alias"><h4 class="service-heading">Lounge Hall</h4></a>
          <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Обучение и услуги -->
  <section class="bg-light page-section" id="learning-et-services">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase"><img class='horus_eye_ico'>Обучение и услуги</h2>
          <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
        </div>
      </div>

      <div class="row learning-et-services-row">
        <div class="col-md-4">
          <a href="#" class="heading_alias"><h4 class="service-heading">Лекции/семинары внутри комплекса</h4></a>
          <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
        </div>
        <div class="col-md-4">
          <a href="#" class="heading_alias"><h4 class="service-heading">Ритуалы</h4></a>
          <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
        </div>
        <div class="col-md-4">
          <a href="#" class="heading_alias"><h4 class="service-heading">Online лекции/семинары</h4></a>
          <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
        </div>
        <div class="col-md-4">
          <a href="#" class="heading_alias"><h4 class="service-heading">Индивидуальное обучение</h4></a>
          <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
        </div>
        <div class="col-md-4">
          <a href="#" class="heading_alias"><h4 class="service-heading">Аренда помещений комплекса</h4></a>
          <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
        </div>
        <div class="col-md-12">
          <a href="#" class="heading_alias"><h3 class="service-heading mt-5 mb-4">Эзотерические услуги</h3></a>

            <h6>Диагностика физического тела, тонких тел, в том числе по фотографии</h6>
            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.  </p>
            <button class="learn-more">
              <div class="circle">
                <span class="icon arrow"></span>
              </div>
              <p class="button-text">Заказать услугу</p>
            </button>

            <h6>Чистка родового канала</h6>
            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.  </p>
            <button class="learn-more">
              <div class="circle">
                <span class="icon arrow"></span>
              </div>
              <p class="button-text">Заказать услугу</p>
            </button>

            <h6>Чистка квартир</h6>
            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.  </p>
            <button class="learn-more">
              <div class="circle">
                <span class="icon arrow"></span>
              </div>
              <p class="button-text">Заказать услугу</p>
            </button>

            <h6>Кармическое целительство</h6>
            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.  </p>
            <button class="learn-more mb-0">
              <div class="circle">
                <span class="icon arrow"></span>
              </div>
              <p class="button-text">Заказать услугу</p>
            </button>
        </div>
      </div>

    </div>
  </section>

  <!-- Отзывы -->
  <section class="page-section" id="responses">
    <div class="container">
      <div class="row speech-bubble-row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase"><img class='horus_eye_ico'>Отзывы о работе в комплексе</h2>
          <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
        </div>
      </div>

      <div class="row speech-bubble-row">
        <div class="speech-bubble-left">
          <h1>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat</h1>
        	<h2>&mdash; Customer Name</h2>
        </div>
      </div>

      <div class="row speech-bubble-row">
        <div class="speech-bubble-right">
          <h1>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat</h1>
          <h2>&mdash; Customer Name</h2>
        </div>
      </div>

    </div>
  </section>

  <!-- Contact -->
  <section class="page-section py-4" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase mb-4">Свяжитесь с нами</h2>
          <!-- <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3> -->
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <form id="contactForm" name="sentMessage" novalidate="novalidate">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <input class="form-control" id="name" type="text" placeholder="Ваше имя" required="required" data-validation-required-message="Пожалуйста, введите имя">
                  <p class="help-block text-danger"></p>
                </div>
                <div class="form-group">
                  <input class="form-control" id="email" type="email" placeholder="Ваш Email" required="required" data-validation-required-message="Пожалуйста, введите email ">
                  <p class="help-block text-danger"></p>
                </div>
                <div class="form-group">
                  <input class="form-control" id="phone" type="tel" placeholder="Ваш номер телефона" required="required" data-validation-required-message="Пожалуйста, введите номер телефона">
                  <p class="help-block text-danger"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <textarea class="form-control" id="message" placeholder="Ваше сообщение" required="required" data-validation-required-message="Пожалуйста, введите сообщение"></textarea>
                  <p class="help-block text-danger"></p>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="col-lg-12 text-center">
                <div id="success"></div>
                <button id="sendMessageButton" class="btn btn-primary btn-xl text-uppercase" type="submit">Отправить сообщение</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-4 mb-3 mb-md-0">
          <span>Телефон: +7(987) 654-32-10</span>
        </div>
        <div class="col-md-4">
          <ul class="list-inline social-buttons">
            <li class="list-inline-item">
              <a href="#" title='Twitter'>
                <i class="fab fa-twitter"></i>
              </a>
            </li>
            <li class="list-inline-item">
              <a href="#" title='Facebook'>
                <i class="fab fa-facebook-f"></i>
              </a>
            </li>
            <li class="list-inline-item">
              <a href="#" title='ВКонтакте'>
                <i class="fab fa-vk"></i>
              </a>
            </li>
            <li class="list-inline-item">
              <a href="#" title='Одноклассники'>
                <i class="fab fa-odnoklassniki"></i>
              </a>
            </li>
          </ul>
        </div>
        <div class="col-md-4">
          <ul class="list-inline quicklinks">
            <li class="list-inline-item">
              <a href="#" style="color: #52A4D5; font-size: 1rem; font-weight: bold">Заказать звонок</a>
            </li>
            <li class="list-inline-item">
              <a href="#" style="color: #52A4D5; font-size: 1rem; font-weight: bold">Позвонить</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Contact form JavaScript -->
  <script src="js/jqBootstrapValidation.js"></script>
  <script src="js/contact_me.js"></script>

  <!-- Custom scripts for this template -->
  <script src="js/agency.js"></script>

  <!-- Carousel script -->
  <script src='/js/carousel.js'></script>
</body>
</html>
