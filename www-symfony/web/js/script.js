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
        items:2,
        loop:false,
        autoplay:true,
         autoplayTimeout:5000,
         autoplayHoverPause:true,
         responsive: {
             0: {
                 items:1
             },
             560: {
                 items: 2
             }
        //     1150: {
        //         items: 3
        //     },
        //     1450: {
        //         items: 4
        //     }
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
    $('#filter_tournament_id').chosen({
        no_results_text: 'No such tournament!'
    });
    $('#filter_user_id').chosen({
        no_results_text: 'No such username!'
    });
    $('#tournament-filter').chosen({
        no_results_text: 'No such tournament!'
    });
    $('#champion_select_team_id').chosen({
        no_results_text: 'No such team!'
    });
});

$(function(){
    $('#filter_date_from').datepicker({
        format : 'yyyy-mm-dd',
        disableTouchKeyboard: true
    });
    $('#filter_date_to').datepicker({
        format : 'yyyy-mm-dd',
        disableTouchKeyboard: true
    });
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

// Bet/update all matches which have a prediction
$( "#btn-bet-all" ).click(function () {
    var matches = [];
    $.each( $('#matches form'), function( index, form ) {
        var matchData = getMatchFormData( form );

        // If the match has a prediction, add it to matches
        if ( matchData.home_goals != "" && matchData.away_goals != "" ) {
            matches.push( matchData );
        }
    });

    $.post("/matches/betall", {matches:matches})
        .done(function(data, textStatus, jqXHR) {
            console.log(data);
            location.reload();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            // log error to browser's console
            console.log(errorThrown.toString());
            alert("Unable to submit data. Please try again.")
        });
});

// Get a form data as a key value object
function getMatchFormData( form ) {
    return $( form ).serializeArray().reduce( function( obj, item ) {
        var name = item.name;
        name = name.replace("form", "").replace("[", "").replace("]","").replace("prediction","");
        obj[ name ] = item.value;
        return obj;
    }, {});
}
