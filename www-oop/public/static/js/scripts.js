// Store all matches those have a prediction

$( "#btn-bet-all" ).click(function () {
    var matches = [];
    $.each( $('#matches form'), function( index, form ) {
        var matchData = getMatchFormData( form );

        // If the match has a prediction, add it to matches
        if ( matchData.home_goals != "" && matchData.away_goals != "" ) {
            matches.push( matchData );
        }
    });

    $.post("index.php?page=matches", {matches:matches})
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
        obj[ item.name ] = item.value;
        return obj;
    }, {});
}
