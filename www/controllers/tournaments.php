<?php

$data = array();

$data['enrolled'] = list_tournaments_enrolled();
$data['not_enrolled'] = list_tournaments_not_enrolled();

view_page($page, $data);
