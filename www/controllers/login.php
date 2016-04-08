<?php

$data = array();

if ( is_form_submitted('login') ) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ( validate_login($email, $password, $login_status) ) {
        login_set($email);
        header("Location: index.php");
    }

    $data = array('login_status' => $login_status);
}

view_page($page, $data);
