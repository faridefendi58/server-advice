/* ==========================================================================
   Page Loader
   ========================================================================== */
   $(window).load(function() {
    "use strict";
    $('#loader').fadeOut();
   });

$(window).on('scroll', function() {
    if ($(window).scrollTop() > 20) {
        $('.header-top-area').addClass('menu-bg');
    } else {
        $('.header-top-area').removeClass('menu-bg');
    }
});

/* ==========================================================================
   Back Top Link
   ========================================================================== */
  var offset = 200;
  var duration = 500;
  $(window).scroll(function() {
    if ($(this).scrollTop() > offset) {
      $('.back-to-top').fadeIn(400);
    } else {
      $('.back-to-top').fadeOut(400);
    }
  });
  $('.back-to-top').click(function(event) {
    event.preventDefault();
    $('html, body').animate({
      scrollTop: 0
    }, 600);
    return false;
  })

  jQuery(function($) {

      // one page navigation 
      $('.main-navigation').onePageNav({
              currentClass: 'active'
      });

      
  });

  jQuery(document).ready(function() {
     
      $('body').scrollspy({
          target: '.navbar-collapse',
          offset: 195
      });

      $(window).on('scroll', function() {
          if ($(window).scrollTop() > 200) {
              $('.fixed-top').addClass('menu-bg');
          } else {
              $('.fixed-top').removeClass('menu-bg');
          }
      });

  });
