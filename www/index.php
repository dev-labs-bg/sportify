<?php

session_start();

require 'includes/functions.php';

$GLOBALS['db_conn'] = App\DB\connect($config);
if ( !$GLOBALS['db_conn'] ) die('Failed to connect to database.');

$requested_page = ( isset($_GET['page']) ? $_GET['page'] : 'standings');
$page = set_page($requested_page);
load_page($page);
