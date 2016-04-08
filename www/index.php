<?php

session_start();

require 'includes/functions.php';
use Mailgun\Mailgun;

$GLOBALS['db_conn'] = App\DB\connect($config);
if ( !$GLOBALS['db_conn'] ) die('Failed to connect to database.');

$http_client = new \Http\Adapter\Guzzle6\Client();
$GLOBALS['mailgun'] = new Mailgun("key-0cd399ac454dce435c5b6e98ff3d14c9", $http_client);
$GLOBALS['mailgun_domain'] = "sandbox57d45177a584422bbf3de22dea6e2768.mailgun.org";

$requested_page = ( isset($_GET['page']) ? $_GET['page'] : 'standings');
$page = set_page($requested_page);
load_page($page);
