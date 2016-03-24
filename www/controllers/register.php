<?php

if ( isset($_POST['form_name']) && $_POST['form_name'] === 'register' ) {
    $email = trim($_POST['email']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (validate_reg($email, $first_name, $last_name, $password, $password_confirm)) {
        echo "Success.";
    } else {
        echo "Fail.";
    }
}

view_page($page);
