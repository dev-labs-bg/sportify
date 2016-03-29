<?php

$data = array();

$user_id = App\DB\get_user_id();

if ( isset($_POST['match_id']) ) {

    if ( validate_prediction($_POST['match_id'], $_POST['home_goals'], $_POST['away_goals']) ) {
        make_prediction($user_id, $_POST['match_id'], $_POST['home_goals'], $_POST['away_goals']);
    }

}

$data['matches'] = list_matches($user_id);

//$data['available'] = list_tournaments_available();

view_page($page, $data);
