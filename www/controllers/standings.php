<?php

$data = array();

$date_from = ( isset($_GET['date_from']) && !empty($_GET['date_from']) ) ? $_GET['date_from'] : "2016-03-31";
$date_to = ( isset($_GET['date_to']) && !empty($_GET['date_to']) ) ? date("Y-m-d", strtotime($_GET['date_to']) + 86400) : date("Y-m-d", time() + 1209600);

$tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : "5";

$data['tournaments'] = list_all_tournaments($tournament_id);
$data['standings'] = list_standings($tournament_id);
$data['tournament_name'] = get_tournament_name($tournament_id);

view_page($page, $data);