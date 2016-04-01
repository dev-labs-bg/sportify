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

//if ( !empty($_GET) ) {
//    foreach ($_GET as &$get_query) {
//        if ( empty($get_query) ) unset($get_query);
//
//    }
//}

$date_from = ( isset($_GET['date_from']) && !empty($_GET['date_from']) ) ? $_GET['date_from'] : date("Y-m-d");
$date_to = ( isset($_GET['date_to']) && !empty($_GET['date_to']) ) ? date("Y-m-d", strtotime($_GET['date_to']) + 86400) : date("Y-m-d", time() + 1209600);

$tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : "ALL";

$data['tournaments'] = list_tournaments_joined();
$data['matches'] = list_matches($user_id, $tournament_id, $date_from, $date_to);

view_page($page, $data);
