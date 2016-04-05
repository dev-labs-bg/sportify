<?php

function validate_reset_input($email, &$password_reset_status = null) {

    if ( empty($email) ) {
        $password_reset_status = 'Please provide e-mail address.';

        return false;
    } else if ( !check_email_in_db($email) ) {
        $password_reset_status = 'No such e-mail address in the system.';

        return false;
    } else {
        $password_reset_status = 'E-mail with password reset link sent to: ' . $email;
    }

    return true;
}

function check_email_in_db($email) {
    $query = App\DB\query(
        "SELECT * FROM users WHERE email = :email",
        array('email' => $email),
        $GLOBALS['db_conn']);

    return ( $query )
        ? true
        : false;
}

function reset_password($email, &$password) {
    $password = random_string_special();

    return App\DB\query(
        "UPDATE users SET password_hash = :password_hash WHERE email = :email",
        array('email' => $email, 'password_hash' => password_hash($password, PASSWORD_DEFAULT)),
        $GLOBALS['db_conn']);
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

//function send_mail($email, $password) {
//    $message = "Password successfully reset. Your new password is: $password";
//    $subject = "Sportify - password reset";
//    $from_email = 'ceco@devlabs.bg';
//    $headers = "From: ${from_email} \r\n";
//    mail($email, $subject, $message, $headers);
//}

function get_userdata_by_token($token) {

    $query = App\DB\query(
        "SELECT users.id, users.email, tokens.purpose, tokens.value, tokens.datetime
        FROM users
        JOIN tokens ON tokens.user_id = users.id AND tokens.value = :token_value",
        array('token_value' => $token),
        $GLOBALS['db_conn']);

    if ($query)
        return $query->fetchAll();

    return array();
}

function validate_userdata_token($userdata, &$password_reset_status = null) {

}