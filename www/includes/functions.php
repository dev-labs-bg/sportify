<?php

define('CTRL_DIR', $_SERVER['DOCUMENT_ROOT'] . '/controllers/');
define('VIEW_DIR', $_SERVER['DOCUMENT_ROOT'] . '/views/');
define('FUNC_DIR', $_SERVER['DOCUMENT_ROOT'] . '/includes/');

require 'db.php';
require 'register_functions.php';
require 'login_functions.php';
require 'tournaments_functions.php';

function set_page($page) {
    $all_access_pages = array('login', 'register', 'standings');

    //    if ( isset($_GET['page']) && in_array($_GET['page'],$all_access_pages) ) {
    //        $page = $_GET['page'];
    //    } else {
    //        if (is_user_logged_in()) {
    //            $page = isset($_GET['page']) ? $_GET['page'] : 'tournaments';
    //        } else {
    //            $page = 'login';
    //        }
    //    }

    if ( !is_user_logged_in() && !in_array($page,$all_access_pages) ) {
        $page = 'login';
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
	return isset($_SESSION['email']);
//	return true;
}

function login_set($email) {
    $_SESSION['email'] = $email;
}

function login_unset() {
    unset($_SESSION['email']);
}

function form_prev_value($item) {
    if ( !empty($_POST[$item]) ) {
        return htmlspecialchars($_POST[$item]);
    }

    return '';
}

function list_matches($user_id) {

}

function list_history($user_id) {

}

