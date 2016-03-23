<?php

require 'includes/functions.php';

$conn = App\DB\connect();
if ( !$conn ) die('Failed to connect to database.');

session_start();

$GLOBALS['login_status'] = is_user_logged_in();

if ( isset($_GET['page']) && ( $_GET['page'] === 'register' || $_GET['page'] === 'standings' ) ) {
	$page = $_GET['page'];
	view_page($page, array("login_status => $login_status"));
} else {
	if ( !is_user_logged_in() ) {
		view_page('login', array("login_status => $login_status"));
	} else {
		if ( isset($_GET['page']) ) {
			$page = $_GET['page'];
		} else {
			// default page for loggen in users
			$page = 'tournaments';
		}
		
		view_page($page, array("login_status => $login_status"));
	}
}


// if ( $_SERVER['REQUEST_METHOD'] === 'GET') {

// } else if ( $_SERVER['REQUEST_METHOD'] === 'POST') {

// }



// var_dump($_SESSION);
// var_dump($_GET);
// if user is logged, view matches


// else, view login screen

	//
	//
	//
