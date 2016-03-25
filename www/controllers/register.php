<?php

$data = array();

if ( isset($_POST['form_name']) && $_POST['form_name'] === 'register' ) {

    $email = trim($_POST['email']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ( validate_reg($email, $first_name, $last_name, $password, $password_confirm, $reg_status) ) {
        login_set($email);
        header("Location: index.php");
    }

    $data = array('reg_status' => $reg_status);
}

view_page($page, $data);
