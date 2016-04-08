<?php

function change_userdata($email, $first_name, $last_name) {
    return App\DB\query(
        "UPDATE users SET first_name = :first_name, last_name = :last_name WHERE email = :email",
        array('email' => $email, 'first_name' => $first_name, 'last_name' => $last_name),
        $GLOBALS['db_conn']);
}
