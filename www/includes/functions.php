<?php

define('CTRL_DIR', $_SERVER['DOCUMENT_ROOT'] . '/controllers/');
define('VIEW_DIR', $_SERVER['DOCUMENT_ROOT'] . '/views/');
define('FUNC_DIR', $_SERVER['DOCUMENT_ROOT'] . '/includes/');

define('POINTS_OUTCOME', 1);
define('POINTS_EXACT', 3);

require 'db.php';
require 'register_functions.php';
require 'login_functions.php';
require 'tournaments_functions.php';
require 'matches_functions.php';
require 'history_functions.php';
require 'standings_functions.php';
require 'scores_update_functions.php';
require 'password_reset_functions.php';

function set_page($page) {
    $all_access_pages = array('login', 'register', 'password_reset', 'standings');

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

function set_date_start(&$var_date_from, $value_default) {
    return ( isset($var_date_from) && !empty($var_date_from) )
        ? $var_date_from
        : $value_default;
}

function set_date_end(&$var_date_to, $sec_offset) {
    return ( isset($var_date_to) && !empty($var_date_to) )
        ? date("Y-m-d", strtotime($var_date_to) + $sec_offset)
        : date("Y-m-d", time() + 1209600);
}

function random_string_special($string_length = 15) {
    $chars_list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&';
    $chars_count = strlen($chars_list);
    $string = '';

    for ($i = 0; $i < $string_length; $i++) {
        $string .= $chars_list[rand(0, $chars_count - 1)];
    }

    return $string;
}

function random_string_alphanum($string_length = 15) {
    $chars_list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars_count = strlen($chars_list);
    $string = '';

    for ($i = 0; $i < $string_length; $i++) {
        $string .= $chars_list[rand(0, $chars_count - 1)];
    }

    return $string;
}

function send_mail($email, $from_email, $subject, $message) {
    $headers = "From: ${from_email} \r\n";
    mail($email, $subject, $message, $headers);
}
