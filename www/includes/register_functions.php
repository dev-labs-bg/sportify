<?php

function validate_reg ($email, $first_name, $last_name, $password, $password_confirm, &$status_message = null) {
    $is_data_invalid = ( empty($email) || empty($first_name) || empty($last_name) || empty($password)
        || empty($password_confirm) || ! valid_email($email) || ($password !== $password_confirm) );

    if ( $is_data_invalid ) {
        $status_message = 'Please provide a first and last names, valid email address and type in same password twice.';

        return false;
    } else if ( !add_user($email, $first_name, $last_name, $password) ) {
        $status_message = 'Email already used. Please provide different email address.';

        return false;
    } else {
        $status_message = 'Thank you for registering. A confirmation e-mail was sent to you.';
    }

    return true;
}

function valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function add_user($email, $first_name, $last_name, $password) {
    return App\DB\query(
            "INSERT IGNORE INTO users(first_name,last_name,email,password_hash)
                VALUES(:first_name, :last_name, :email, :password_hash)",
            array('first_name' => $first_name, 'last_name' => $last_name, 'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT)),
            $GLOBALS['db_conn']);
}

function confirm_registration($email) {
    return App\DB\query(
        "UPDATE users SET confirmed = 1 WHERE email = :email",
        array('email' => $email),
        $GLOBALS['db_conn']);
}