<?php

$data = array();

if ( isset($_POST['form_name']) && $_POST['form_name'] === 'login' ) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ( validate_login($email, $password, $login_status) ) {
        login_set($email);
        header("Location: index.php");
    }

    $data = array('login_status' => $login_status);
}

view_page($page, $data);
