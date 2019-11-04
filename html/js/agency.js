(function($) {
  "use strict"; // Start of use strict

  // Smooth scrolling using jQuery easing
  $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: (target.offset().top - 54)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  // Closes responsive menu when a scroll trigger link is clicked
  $('.js-scroll-trigger').click(function() {
    $('.navbar-collapse').collapse('hide');
  });

  // Activate scrollspy to add active class to navbar items on scroll
  $('body').scrollspy({
    target: '#mainNav',
    offset: 56
  });

  // Collapse Navbar
  var navbarCollapse = function() {
    if ($("#mainNav").offset().top > 100) {
      $("#mainNav").addClass("navbar-shrink");
    } else {
      $("#mainNav").removeClass("navbar-shrink");
    }
  };
  // Collapse now if page is not at top
  navbarCollapse();
  // Collapse the navbar when page is scrolled
  $(window).scroll(navbarCollapse);

})(jQuery); // End of use strict

/************************/
function add_elements(e){
    e.preventDefault();
    e.stopPropagation();
    var elems = document.getElementsByClassName('pyramid_nav_subdropdown');
    if(elems[0].style.display == 'block'){
      for(i in elems) elems[i].style.display = 'none';
      return;
    }
    for(i in elems) elems[i].style.display = 'block';
 }
/**************************/
var navbar_buttons = {
  'main' : {
    id : 'navbarResponsive',
    status : false,
  },
  'pyramid' : {
    id : 'pyramid_navbar_mob',
    status : false,
  }
}

function show_navbar(button){
  if(navbar_buttons[button].status == true){
    navbar_buttons[button].status = false;
    document.getElementById(navbar_buttons[button].id).style.display = 'none';
    return;
  }

  for(i in navbar_buttons){
    if(i != button && navbar_buttons[i].status != false){
      navbar_buttons[i].status = false;
      document.getElementById(navbar_buttons[i].id).style.display = 'none';
    }
  }

  navbar_buttons[button].status = true;
  document.getElementById(navbar_buttons[button].id).style.display = 'block';
}
/*******************************/
window.addEventListener('load', slick_init);
function slick_init(){
  console.log('Starting slick carousel initialisation...');
  
}
