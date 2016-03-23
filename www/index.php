<?php

require 'includes/functions.php';

// $conn = App\DB\connect();
// if ( !$conn ) die('Failed to connect to database.');

session_start();

if ( !is_user_logged_in() ) {
	view_page('login');
} else {
	if ( isset($_GET['page']) ) {
		$page = $_GET['page'];
	} else {
		$page = 'tournaments';
	}
		
	view_page($page);

}


// if user is logged, view matches


// else, view login screen

	//
	//
	//
