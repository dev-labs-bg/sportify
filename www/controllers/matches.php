<?php

$data = array();

$user_id = App\DB\get_user_id($_SESSION['email']);

if ( isset($_POST['match_id']) ) {
    $is_prediction_valid = validate_prediction($_POST['match_id'], $_POST['home_goals'], $_POST['away_goals'], $prediction_status);

    if ( $is_prediction_valid ) {
        make_prediction($user_id, $_POST['match_id'], $_POST['home_goals'], $_POST['away_goals']);
    }

    $data['prediction_value'] = $is_prediction_valid;
    $data['prediction_status'] = $prediction_status;
    $data['match_id'] = $_POST['match_id'];
}

$date_from = set_date_start($_GET['date_from'], date("Y-m-d"));
$date_to = set_date_end($_GET['date_to'], 86400);

$tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : "ALL";

$data['tournaments'] = list_tournaments_joined($tournament_id);
$data['matches'] = list_matches($user_id, $tournament_id, $date_from, $date_to);

view_page($page, $data);
