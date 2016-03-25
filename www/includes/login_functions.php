<?php

function validate_login($email, $password, &$login_status = null) {
    $is_data_invalid = ( empty($email) || empty($password) );

    if ( $is_data_invalid ) {
        $login_status = 'Please provide both email and password.';

        return false;
    } else if ( !check_user($email, $password) ) {
        $login_status = 'Incorrect username or password.';

        return false;
    } else {
        $login_status = 'Thank you for logging in.';
    }

    return true;
}

function check_user($email, $password) {
    $query = App\DB\query(
        "SELECT * FROM users WHERE email = :email",
        array('email' => $email),
        $GLOBALS['db_conn']);

    if ( $query ) {
        $password_hash = $query->fetchAll()[0]['password_hash'];
        return password_verify($password, $password_hash);
    } else {
        return false;
    }
}