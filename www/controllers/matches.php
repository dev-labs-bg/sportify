<?php

$data = array();

$user_id = App\DB\get_user_id();

if ( isset($_POST['match_id']) ) {

    if (validate_prediction($_POST['match_id'], $_POST['home_goals'], $_POST['away_goals'])) {
        make_prediction($user_id, $_POST['match_id'], $_POST['home_goals'], $_POST['away_goals']);
    }

}

$data['tournaments'] = list_tournaments_joined();

$tournament_id = ( isset($_POST['tournament_id']) ) ? $_POST['tournament_id'] : "ALL";
$data['matches'] = list_matches($user_id, $tournament_id);

view_page($page, $data);
