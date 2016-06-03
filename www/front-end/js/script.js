$(document).ready(function () {
    var trigger = $('.hamburger'),
        isClosed = true;

    trigger.click(function () {
        hamburger_cross();
    });

    function hamburger_cross() {

        if (isClosed == true) {
          trigger.removeClass('is-open');
          trigger.addClass('is-closed');
          isClosed = false;
        } else {
          trigger.removeClass('is-closed');
          trigger.addClass('is-open');
          isClosed = true;
        }
    }

    $('[data-toggle="offcanvas"]').click(function () {
          $('#wrapper').toggleClass('toggled');
    });
});

$(function(){
    $(".current-results-slider").owlCarousel({
        items:4,
        loop:true,
        autoplay:true,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        responsive: {
            0: {
                items:1
            },
            480: {
                items: 2
            },
            768: {
                items:3
            },
            992: {
                items: 4
            }
        }
    });
})

$(function(){
    if ($('#page-content-wrapper').length > 0) {
        //Fixed Header - bottom border appears on Scroll
        var elementPosition = $('#page-content-wrapper').offset().top;
        var scrollTimeout;  // global for any pending scrollTimeout
        var $window = $(window);
        $navbar = $('#navbar');


        $window.scroll(function(){
              if (scrollTimeout) {
                  // clear the timeout, if one is pending
                  clearTimeout(scrollTimeout);
                  scrollTimeout = null;
              }
              scrollTimeout = setTimeout(scrollHandler, 250);
        });

        var scrollHandler = function(){
            var topWindow = $window.scrollTop();

            if (topWindow > elementPosition) {
                $navbar.addClass('scrolled');
            }
            else if (topWindow === elementPosition) {
                $navbar.removeClass('scrolled');
            }
        };
    }
})
