<?php

function validate_reg ($email, $first_name, $last_name, $password, $password_confirm, &$reg_status = null) {
    $is_data_invalid = ( empty($email) || empty($first_name) || empty($last_name) || empty($password)
        || empty($password_confirm) || ! valid_email($email) || ($password !== $password_confirm) );

    if ( $is_data_invalid ) {
        $reg_status = 'Please provide a first and last names, valid email address and type in same password twice.';

        return false;
    } else if ( !add_user($email, $first_name, $last_name, $password) ) {
        $reg_status = 'Email already used. Please provide different email address.';

        return false;
    } else {
        $reg_status = 'Thank you for registering.';
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
