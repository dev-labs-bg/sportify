<?php

function validate_password_reset_input($email, &$status_message = null) {

    if ( empty($email) ) {
        $status_message = 'Please provide e-mail address.';

        return false;
    } else if ( !check_email_in_db($email) ) {
        $status_message = 'No such e-mail address in the system.';

        return false;
    } else {
        $status_message = 'E-mail with password reset link sent to: ' . $email;
    }

    return true;
}

function check_email_in_db($email) {
    $query = App\DB\query(
        "SELECT * FROM users WHERE email = :email",
        array('email' => $email),
        $GLOBALS['db_conn']);

    return (bool) $query;
}

function validate_password($password, $password_confirm, &$status_message = null) {

    if ( empty($password) || empty($password_confirm) || $password !== $password_confirm ) {
        $status_message = 'Please type in same password twice.';

        return false;
    } else {
//        $status_message = 'Passwords match.';
    }

    return true;
}

function change_password($email, $password) {
    return App\DB\query(
        "UPDATE users SET password_hash = :password_hash WHERE email = :email",
        array('email' => $email, 'password_hash' => password_hash($password, PASSWORD_DEFAULT)),
        $GLOBALS['db_conn']);
}
