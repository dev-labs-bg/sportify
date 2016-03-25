<?php

$data = array();

$data[] = list_tournaments_enrolled();
$data[] = list_tournaments_not_enrolled();

view_page($page, $data);
