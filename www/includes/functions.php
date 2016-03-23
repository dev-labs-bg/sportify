<?php

require 'db.php';


function view_page($view, $data = null) {
	if ( $data ) {
		extract($data);
	}

	include "views/header.php";
	include "views/{$view}.view.php";
	include "views/footer.php";
}

function is_user_logged_in() {
	return isset($_SESSION['username']);
	// return true;
}

function validate_login() {

}

function list_tournaments($user_id) {

}

function list_matches($user_id) {

}

function list_history($user_id) {

}

