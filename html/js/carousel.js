window.addEventListener('load', ()=>{
  if(carousel.init('main_carousel', 'carousel_announce_wrap')){
    carousel.container.addEventListener('mousedown', carousel.onmousedown);
    carousel.container.addEventListener('touchstart', carousel.onmousedown);
  }
})

var carousel = {
  'init' : function(carouselContainerId, carouselCardsClass){
    carousel.container = document.getElementById(carouselContainerId);    // родительский контейнер, в котором показываются элементы карусели
    carousel.elems =  carousel.container.getElementsByClassName(carouselCardsClass);      // все элементы (слайды) карусели
    carousel.width = carousel.container.offsetWidth;           // ширина контейнера карусели
    carousel.length = carousel.elems.length;      // количество слайдов карусели

    if(carousel.length == 1) return false;   // если слайд лишь один, то инициализация карусели прекращается

    carousel.mousedown = false;     // удерживается ли кнопка / нажата ли точка на тачпаде
    carousel.mouseStart = 0;        // исходная координата курсора (точки нажатия на тачпаде) относительно оси Х в момент срабатывания onmousedown / ontouchstart


    carousel.offset = {
      'curr' : 0,           // текущие координаты курсора по оси Х
      'left' : -carousel.width,   // положение левого слайда относительно левого края родительского контейнера в исходный момент
      'right' : carousel.width,   // положение правого
      'center' : 0,               // положение центрального
      'change_el_required' : (carousel.width/3),    // минимальное смещение на момент отпуска кнопки / тачпада ( срабатывания onmouseup / ontouchend ), необходимое для смены слайда
    };

    // определяем активные слайды
    carousel.set_actives(0);

    carousel.indicators = carousel.add_indicators();    // массив индикаторов слайдов

    return true;    // карусель инициализована
  },


  'init_el' : function(num){      // функция инициализации объектов предыдущего/текущего/последующего слайдов
    var el = carousel.elems[num];
    el.style.display = 'block';
    return {
      'num' : num,     // номер слайда
      'el' : el        // DOM-элемент слайда
    };
  },

  'add_indicators' : function(){      // функция добавления индикатора слайдов
    var carouselIndicatorWrap = document.createElement('div');
    var carouselIndicatorArray = [];
    carouselIndicatorWrap.setAttribute('style', 'position: absolute; bottom: 2%; width: 100%; text-align: center;');

    var temp_span = {};
    for(i=0; i!=carousel.length; ++i){
      temp_span = document.createElement('span');
      if(i==carousel.curr.num){
        temp_span.setAttribute('style', 'background-color: white; border: 1px solid white; border-radius: 50%; height:10px; width:10px; margin: 0 2px; display: inline-block');
      }
      else{
        temp_span.setAttribute('style', 'background-color: transparent; border: 1px solid white; border-radius: 50%; height:8px; width:8px; margin: 0 2px; display: inline-block');
      }
      temp_span.id = 'carousel_indicator'+i;
      carouselIndicatorWrap.appendChild(temp_span);
      carouselIndicatorArray.push(temp_span);
    }
    carousel.container.appendChild(carouselIndicatorWrap);

    return carouselIndicatorArray;  // возвращаем массив, в котором каждый элемент - индикатор слайда
  },

  'set_actives' : function(curr_num){   // функция установки активных слайдов
    if(curr_num < 0) curr_num = (carousel.length - 1);
    else if(curr_num > (carousel.length-1)) curr_num = 0;

    carousel.curr = carousel.init_el(curr_num);

    if(curr_num == 0){
      carousel.next = carousel.init_el(1);
      carousel.prev = carousel.init_el(carousel.length - 1);
    }
    else if(curr_num == (carousel.length-1)){
      carousel.next = carousel.init_el(0);
      carousel.prev = carousel.init_el(curr_num-1)
    }
    else{
      carousel.next = carousel.init_el(curr_num+1);
      carousel.prev = carousel.init_el(curr_num-1);
    }

    // делаем невидимыми все слайды кроме активных
    for(i=0; i!=carousel.length; ++i){
      if(i!=carousel.prev.num && i!=carousel.next.num && i!=carousel.curr.num){
        carousel.elems[i].style.display = 'none';
      }
    }

    // устанавливаем смещение активных слайдов
    carousel.curr.el.style.left = '0';
    carousel.next.el.style.left = carousel.offset.right + 'px';
    carousel.prev.el.style.left = carousel.offset.left + 'px';
  },

  'onmousedown' : function(e){    // обработка события нажатия на кнопки / тачпад
    carousel.mousedown = true;    // пока удерживается кнопка / точка на тачпаде - переменная остается true
    if(typeof(e.touches) != 'undefined') carousel.mouseStart = e.touches[0].clientX;    // получение координат точки нажатия тачпада
    else carousel.mouseStart = e.clientX;   // получение координат точки нажатия кнопки
    // Обработчики событий отуска и перемещения кнопки для мыши
    window.addEventListener('mouseup', carousel.onmouseup, { 'once':true });
    window.addEventListener('mousemove', carousel.onmousemove);
    // Обработчики событий отуска и перемещения для тачпада
    window.addEventListener('touchend', carousel.onmouseup, { 'once':true });
    window.addEventListener('touchmove', carousel.onmousemove);
  },

  'onmousemove' : function(e){    // функция обработки перемещения курсора с зажатой кнопкой мыши
      // смещение (дельта?) текущего положения мыши/точки на тачпаде по оси Х относительно положения на момент нажатия кнопки
      if(typeof(e.touches) != 'undefined') carousel.offset.curr = carousel.mouseStart - e.touches[0].clientX;
      else carousel.offset.curr = (carousel.mouseStart - e.clientX);

      if(carousel.offset.curr > 0){     // обрабатываем перемещение курсора влево
        carousel.move_el('next');   // перемещаем активные элементы влево на значение смещения курсора
      }
      else if(carousel.offset.curr < 0){    // то же, только вправо
        carousel.move_el('prev');
      }
  },

  'onmouseup' : function(){   // как только была отпущена кнока мыши / точка на тачпаде
    window.removeEventListener('mousemove', carousel.onmousemove);    // прекратить отслеживание движений курсора для мыши
    window.removeEventListener('touchmove', carousel.onmousemove);    // и для тачпада

    if(carousel.offset.curr >= carousel.offset.change_el_required ){  // если смещение достаточно - вызываем смену элемента
      carousel.change_el('next');
    }
    else if(carousel.offset.curr <= -carousel.offset.change_el_required ){    // если смещение достаточно - вызываем смену элемента
      carousel.change_el('prev');
    }
    else{       // в противном случае возвращаем все элементы в исходное положение на момент до нажатия
      carousel.set_actives(carousel.curr.num);
    }

    carousel.mousedown = false;   // показываем, что кнопка/точка на тачпаде больше не зажата
    carousel.offset.curr = 0;     // обнуляем значение смещения курсора
  },

  'change_el' : function(direction){
    if(direction == 'next'){    // делаем активным следующий элемент
      ++carousel.curr.num;      // увеличиваем значение номера активного элемента
    }
    else{     // делаем активным предыдущий элемент
      --carousel.curr.num;    // уменьшаем значение номера активного элемента
    }

    carousel.set_actives(carousel.curr.num);    // производим настройку активных слайдов

    for(i=0; i!=carousel.length; ++i){      // переопределяем индикаторы
      if(i==carousel.curr.num){
        carousel.indicators[i].style.height = carousel.indicators[i].style.width = '10px';
        carousel.indicators[i].style.backgroundColor = 'white';
      }
      else{
        carousel.indicators[i].style.height = carousel.indicators[i].style.width = '8px';
        carousel.indicators[i].style.backgroundColor = 'transparent';
      }
    }
  },


  'move_el' : function(direction){      //  функция перемещения элемента по оси Х
      if(carousel.offset.curr < (-carousel.width)){   // если смещение больше ширины окна - снижаем до ширины окна
        carousel.offset.curr = (-carousel.width);     // таким образом можно пролистать не больше одного слайда за раз
        return;
      }
      if(carousel.offset.curr > carousel.width){    // то же самое, только в другую сторону
        carousel.offset.curr = carousel.width;
        return;
      }

      carousel.curr.el.style.left = (-carousel.offset.curr) + 'px';     // смещаем текущий элемент согласно движению курсора
      carousel.next.el.style.left = (carousel.width - carousel.offset.curr) + 'px';       // смещаем следующий элемент
      carousel.prev.el.style.left = -(carousel.width + carousel.offset.curr) + 'px';      // смещаем предыдущий элемент
    },

};
