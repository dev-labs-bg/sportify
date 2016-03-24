<?php

function validate_reg ($email, $first_name, $last_name, $password, $password_confirm, &$reg_status = null) {
    $is_data_invalid = ( empty($email) || empty($first_name) || empty($last_name) || empty($password)
        || empty($password_confirm) || ! valid_email($email) || ($password !== $password_confirm) );

    if ( $is_data_invalid ) {
        $reg_status = 'Please provide a first and last names, valid email address and type in same password twice.';
        echo $reg_status;
        
        return false;
    } else if (!add_user($email, $first_name, $last_name, $password)) {
        $reg_status = 'Email already used. Please provide different email address.';
        echo $reg_status;

        return false;
    } else {
        $reg_status = 'Thank you for registering.';
        echo $reg_status;
    }

    return true;
}

function valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

//function duplicate_email($email) {
//    $users = file(MAILING_LIST);
//
//
//    if ( count($users) ) {
//        $emails = array();
//        foreach ($users as $row) {
//            $tmp_array = explode(': ', htmlspecialchars(trim($row)));
//            array_push($emails, $tmp_array[1]);
//        }
//
//        return in_array($email, $emails);
//    }
//
//    return false;
//}

function add_user($email, $first_name, $last_name, $password) {
    return App\DB\query(
            "INSERT IGNORE INTO users(first_name,last_name,email,password_hash)
                VALUES(:first_name, :last_name, :email, :password_hash)",
            array('first_name' => $first_name, 'last_name' => $last_name, 'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT)),
            $GLOBALS['db_conn']);
}
