<?php

$data = array();

$user_id = App\DB\get_user_id($_SESSION['email']);

$date_from = set_date_start($_GET['date_from'], "2016-03-31");
$date_to = set_date_end($_GET['date_to'], 86400);

$tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : "ALL";

$data['tournaments'] = list_tournaments_joined();
$data['matches'] = list_scored_matches($user_id, $tournament_id, $date_from, $date_to);

view_page($page, $data);
