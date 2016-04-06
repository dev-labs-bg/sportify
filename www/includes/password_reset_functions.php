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

function token_db_insert($email, $purpose, $token) {
    $user_id = App\DB\get_user_id($email);

    date_default_timezone_set('EET');
    $datetime = date('Y-m-d H:i:s');

    return App\DB\query(
        "INSERT INTO tokens (user_id, purpose, value, datetime)
        VALUES (:user_id, :token_purpose, :token_value, :token_time)
        ON DUPLICATE KEY UPDATE value = :token_value, datetime = :token_time",
        array('user_id' => $user_id, 'token_purpose' => $purpose, 'token_value' => $token, 'token_time' => $datetime),
        $GLOBALS['db_conn']);
}

function get_userdata_by_token($token) {

    $query = App\DB\query(
        "SELECT users.id as user_id, users.email,
                tokens.purpose as token_purpose, tokens.value as token_value, tokens.datetime as token_datetime
        FROM users
        JOIN tokens ON tokens.user_id = users.id AND tokens.value = :token_value",
        array('token_value' => $token),
        $GLOBALS['db_conn']);

    if ($query)
        return $query->fetchAll()[0];

    return array();
}

function validate_userdata_token($userdata, &$status_message = null) {

    if ( empty($userdata) ) {
        $status_message = 'Invalid token ID.';

        return false;
    } else if ( !check_token_validity($userdata['token_datetime']) ) {
        $status_message = 'Token ID has expired.';

        return false;
    } else {
//        $status_message = 'Valid token ID.';
    }

    return true;
}

function check_token_validity($datetime, $lifetime = 1800) {
    date_default_timezone_set('EET');

    return ( time() - strtotime($datetime) < $lifetime )
        ? true
        : false;
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

function clear_token($email, $token_purpose) {
    $user_id = App\DB\get_user_id($email);

    return App\DB\query(
        "DELETE FROM tokens WHERE user_id = :user_id AND purpose = :token_purpose",
        array('user_id' => $user_id, 'token_purpose' => $token_purpose),
        $GLOBALS['db_conn']);
}