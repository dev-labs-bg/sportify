<?php

$data = array();

$user_id = App\DB\get_user_id();

$date_from = ( isset($_GET['date_from']) && !empty($_GET['date_from']) ) ? $_GET['date_from'] : "2016-03-31";
$date_to = ( isset($_GET['date_to']) && !empty($_GET['date_to']) ) ? date("Y-m-d", strtotime($_GET['date_to']) + 86400) : date("Y-m-d", time() + 1209600);

$tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : "ALL";

$data['tournaments'] = list_tournaments_joined();
$data['matches'] = list_scored_matches($user_id, $tournament_id, $date_from, $date_to);

view_page($page, $data);
