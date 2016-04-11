<?php

$data = array();

$user_id = App\DB\get_user_id($_SESSION['email']);

// if the page is submitted via a single match BET/UPDATE button
if ( isset($_POST['match_id']) ) {
    $match_id = $_POST['match_id'];
    $home_goals = $_POST['home_goals'];
    $away_goals = $_POST['away_goals'];

    $is_prediction_valid = validate_prediction($match_id, $home_goals, $away_goals, $prediction_status);

    if ( $is_prediction_valid ) {
        make_prediction($user_id, $match_id, $home_goals, $away_goals);
    }

    $data['prediction_value'] = $is_prediction_valid;
    $data['prediction_status'] = $prediction_status;
    $data['match_id'] = $match_id;
}

// if the page is submitted via the BET/UPDATE ALL button
if ( isset($_POST['matches']) ) {
    foreach ($_POST['matches'] as $row) {
        $match_id = $row['match_id'];
        $home_goals = $row['home_goals'];
        $away_goals = $row['away_goals'];

        $is_prediction_valid = validate_prediction($match_id, $home_goals, $away_goals, $prediction_status);

        if ($is_prediction_valid) {
            make_prediction($user_id, $match_id, $home_goals, $away_goals);
        }

//        $data['prediction_value'] = $is_prediction_valid;
//        $data['prediction_status'] = $prediction_status;
//        $data['match_id'] = $match_id;
    }
}

$date_from = set_date_start($_GET['date_from'], date("Y-m-d"));
$date_to = set_date_end($_GET['date_to'], 86400);

$tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : "ALL";

$data['tournaments'] = list_tournaments_joined($tournament_id);
$data['matches'] = list_matches($user_id, $tournament_id, $date_from, $date_to);

view_page($page, $data);
