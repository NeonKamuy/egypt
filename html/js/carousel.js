window.addEventListener('load', ()=>{
  if(carousel.init('main_carousel', 'carousel_anounce_wrap')){
    carousel.container.addEventListener('mousedown', carousel.onmousedown);
    carousel.container.addEventListener('touchstart', carousel.onmousedown);
  }
})

var carousel = {
  'init' : function(carouselContainerId, carouselCardsClass){
    carousel.container = document.getElementById(carouselContainerId);
    carousel.width = carousel.container.offsetWidth;
    carousel.elems =  carousel.container.getElementsByClassName(carouselCardsClass);
    carousel.length = carousel.elems.length;
    carousel.mousedown = false;
    carousel.mouseStart = 0;


    carousel.offset = { 'curr' : 0, 'first' : 0 };

    carousel.prev = 'none';
    carousel.curr = carousel.init_el(0);
    carousel.next = carousel.init_el(1);

    if(carousel.length == 1) return false;   // only one card, no carousel

    carousel.indicators = carousel.add_indicators();

    var elem_offset = 0;
    for(i=0; i!=carousel.length; ++i){
      carousel.elems[i].style.left = elem_offset + 'px';
      elem_offset += carousel.width;
    }

    return true;    // carousel initialised
  },

  'init_el' : function(num){  return {'num' : num, 'el' : carousel.elems[num]};  },

  'add_indicators' : function(){
    var carouselIndicatorWrap = document.createElement('div');
    var carouselIndicatorArray = [];
    carouselIndicatorWrap.setAttribute('style', 'position: absolute; bottom: 2%; width: 100%; text-align: center; padding: 0% 10%');

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

    return carouselIndicatorArray;
  },

  'check_surrounding' : function(){
    if(carousel.curr.num == carousel.length-1){
      carousel.next = 'none';
    }
    else{
      carousel.next = carousel.init_el(carousel.curr.num+1);
    }

    if(carousel.curr.num == 0){
      carousel.prev = 'none';
    }
    else{
      carousel.prev = carousel.init_el(carousel.curr.num-1);
    }
  },

  'onmousedown' : function(e){
    carousel.mousedown = true;
    carousel.mouseStart = e.clientX;
    window.addEventListener('mouseup', carousel.onmouseup, { 'once':true });
    window.addEventListener('mousemove', carousel.onmousemove);

    window.addEventListener('touchend', carousel.onmouseup, { 'once':true });
    window.addEventListener('touchmove', carousel.onmousemove);
  },

  'onmousemove' : function(e){
      carousel.offset.curr = (carousel.mouseStart - e.clientX);

      if(carousel.offset.curr > 0){
        if(carousel.next == 'none'){  return;  }
        carousel.move_el('next');
      }
      else if(carousel.offset.curr < 0){
        if(carousel.prev == 'none'){ return; }
        carousel.move_el('prev');
      }
  },

  'onmouseup' : function(){
    window.removeEventListener('mousemove', carousel.onmousemove);
    window.removeEventListener('touchmove', carousel.onmousemove);

    if(carousel.offset.curr >= 150 && carousel.next != 'none'){
      carousel.change_el('next');
    }
    else if(carousel.offset.curr <= -150 && carousel.prev != 'none'){
      carousel.change_el('prev');
    }
    else{
      var temp_offset = carousel.offset.first;
      for(i=0; i!=carousel.length; ++i){
        carousel.elems[i].style.left = temp_offset + 'px';
        temp_offset += carousel.width;
      }
    }

    for(i=0; i!=carousel.length; ++i){
      if(i==carousel.curr.num){
        carousel.indicators[i].style.height = carousel.indicators[i].style.width = '10px';
        carousel.indicators[i].style.backgroundColor = 'white';
      }
      else{
        carousel.indicators[i].style.height = carousel.indicators[i].style.width = '8px';
        carousel.indicators[i].style.backgroundColor = 'transparent';
      }
    }

    carousel.mousedown = false;
    carousel.offset.curr = 0;
  },

  'change_el' : function(direction){
    if(direction == 'next'){
      ++carousel.curr.num;
      carousel.offset.first -= carousel.width;
    }
    else{
      --carousel.curr.num;
      carousel.offset.first += carousel.width;
    }

    carousel.check_surrounding();
    carousel.curr.el = carousel.elems[carousel.curr.num];

    var temp_offset = carousel.offset.first;
    for(i=0; i!=carousel.length; ++i){
      carousel.elems[i].style.left = temp_offset + 'px';
      temp_offset += carousel.width;
    }
  },

  'move_el' : function(direction){
    if(carousel.offset.curr < -carousel.width){
      carousel.offset.curr = -carousel.width;
      return;
    }
    if(carousel.offset.curr > carousel.width){
      carousel.offset.curr = carousel.width;
      return;
    }

    carousel.curr.el.style.left = (-carousel.offset.curr) + 'px';
    if(carousel.next != 'none') carousel.next.el.style.left = (carousel.width - carousel.offset.curr) + 'px';
    if(carousel.prev != 'none') carousel.prev.el.style.left = -(carousel.width + carousel.offset.curr) + 'px';
  },
};
