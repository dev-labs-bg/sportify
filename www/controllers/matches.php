<?php

$data = array();

$user_id = App\DB\get_user_id();

if ( isset($_POST['match_id']) ) {
    $is_prediction_valid = validate_prediction($_POST['match_id'], $_POST['home_goals'], $_POST['away_goals'], $prediction_status);

    if ( $is_prediction_valid ) {
        make_prediction($user_id, $_POST['match_id'], $_POST['home_goals'], $_POST['away_goals']);
    }

    $data['prediction_value'] = $is_prediction_valid;
    $data['prediction_status'] = $prediction_status;
    $data['match_id'] = $_POST['match_id'];
}

$data['tournaments'] = list_tournaments_joined();

$tournament_id = ( isset($_GET['tournament_id']) ) ? $_GET['tournament_id'] : "ALL";
$data['matches'] = list_matches($user_id, $tournament_id);

view_page($page, $data);
