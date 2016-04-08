<?php

$data = array();

if ( is_form_submitted('profile_change') ) {

    $email = $_SESSION['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    change_userdata($email, $first_name, $last_name);
    
    if ( empty($password) && empty($password_confirm) ) {
        $status_message = 'You have successfully changed your profile details.';
    } else if ( validate_password($password, $password_confirm, $status_message) ){
        change_password($email, $password);
    }

    $data['status_message'] = $status_message;
}

$data['userdata'] = get_userdata_by_email($_SESSION['email']);

view_page($page, $data);
