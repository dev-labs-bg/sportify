<?php

session_start();

require 'includes/functions.php';
use Mailgun\Mailgun;

$GLOBALS['db_conn'] = App\DB\connect();
if ( !$GLOBALS['db_conn'] ) die('Failed to connect to database.');

$http_client = new \Http\Adapter\Guzzle6\Client();
$GLOBALS['mailgun'] = new Mailgun(getenv('MAILGUN_API_KEY'), $http_client);

$requested_page = ( isset($_GET['page']) ? $_GET['page'] : 'standings');
$page = set_page($requested_page);
load_page($page);
