<?php

session_start();

require 'includes/functions.php';

$GLOBALS['db_conn'] = App\DB\connect($config);
if ( !$GLOBALS['db_conn'] ) die('Failed to connect to database.');

//$GLOBALS['login_status'] = is_user_logged_in();

//if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {

$requested_page = ( isset($_GET['page']) ? $_GET['page'] : 'standings');
$page = set_page($requested_page);
load_page($page);

//} else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
//
//    switch($_SERVER['QUERY_STRING']) {
//        case 'page=register':
//            require 'register.php';
//            break;
//    }
//
//}


//var_dump($_SERVER['DOCUMENT_ROOT']);
//var_dump($_SERVER['REQUEST_URI']);
//var_dump($_SERVER['QUERY_STRING']);
// var_dump($_SESSION);
// var_dump($_GET);
//var_dump($_POST);
// if user is logged, view matches

// else, view login screen

	//
	//
	//
