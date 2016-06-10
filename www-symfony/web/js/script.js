$(document).ready(function () {
    var trigger = $('.hamburger'),
        isClosed = true;

    trigger.click(function () {
        hamburger_cross();
    });

    function hamburger_cross() {

        if (isClosed === true) {
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
    $('.current-results-slider').owlCarousel({
        items:4,
        loop:true,
        autoplay:true,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        responsive: {
            0: {
                items:1
            },
            560: {
                items: 2
            },
            1150: {
                items: 3
            },
            1450: {
                items: 4
            }
        }
    });
});
$(function(){
    $('.standings-slider').owlCarousel({
        items:3,
        loop:false,
        responsive: {
            0: {
                items:1,
                loop:true,
                autoplay:true,
                autoplayTimeout:5000
            },
            1450: {
                items: 3
            }
        }
    });
});

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
});

$(function(){
    $('#form_tournament_id').chosen({
        no_results_text: 'No such tournament!'
    });
    $('#form_user').chosen({
        no_results_text: 'No such username!'
    });
    $('#tournament-filter').chosen({
        no_results_text: 'No such tournament!'
    });
});

$(function(){
    $('#form_date_from').datepicker({
        format : 'yyyy-mm-dd'
    });
    $('#form_date_to').datepicker({
        format : 'yyyy-mm-dd'
    });
});