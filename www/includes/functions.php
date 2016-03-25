<?php

define('CTRL_DIR', $_SERVER['DOCUMENT_ROOT'] . '/controllers/');
define('VIEW_DIR', $_SERVER['DOCUMENT_ROOT'] . '/views/');
define('FUNC_DIR', $_SERVER['DOCUMENT_ROOT'] . '/includes/');

require 'db.php';
require 'register_functions.php';
require 'login_functions.php';

function get_page() {
    if ( isset($_GET['page']) && ($_GET['page'] === 'register' || $_GET['page'] === 'standings') ) {
        $page = $_GET['page'];
    } else {
        if (is_user_logged_in()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 'tournaments';
        } else {
            $page = 'login';
        }
    }

    return $page;
}

function load_page($page) {
    require (CTRL_DIR . "${page}.php");
}

function view_page($page, $data = null) {
	if ( $data ) {
		extract($data);
	}

	include VIEW_DIR . "header.php";
	include VIEW_DIR . "{$page}.view.php";
	include VIEW_DIR . "footer.php";
}

function is_user_logged_in() {
	return isset($_SESSION['username']);
//	return true;
}

function login_set($username) {
    $_SESSION['username'] = $username;
}

function login_unset() {
    unset($_SESSION['username']);
}

function form_prev_value($item) {
    if ( !empty($_POST[$item]) ) {
        return htmlspecialchars($_POST[$item]);
    }

    return '';
}

function list_tournaments($user_id) {

}

function list_matches($user_id) {

}

function list_history($user_id) {

}

