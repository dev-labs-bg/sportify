<?php

$data = array();

if ( is_form_submitted('register') ) {

    $email = trim($_POST['email']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ( validate_reg($email, $first_name, $last_name, $password, $password_confirm, $status_message) ) {
//        login_set($email);
//        header("Location: index.php");
        $token = random_string_alphanum(30);
        token_db_insert($email, 'register_confirm', $token);

        $url = get_site_url() . '&token=' . $token;
        $from_email = 'ceco@devlabs.bg';
        $subject = 'Sportify - user registration request at ' . get_datetime_string(time());
        $message = load_view('html_mail_token_link.php',
                            array('INFORMATIVE_TEXT','URL_TOKEN'),
                            array('Please follow this link to confirm your registration',$url));
        send_mail($email, $from_email, $subject, $message);

    }

    $data['status_message'] = $status_message;
}

if ( isset($_GET['token']) && !empty($_GET['token']) ) {
    $data['userdata'] = get_userdata_by_token($_GET['token']);

    if ( validate_userdata_token($data['userdata'], 'register_confirm', $status_message) ) {
        confirm_registration($data['userdata']['email']);
        clear_token($data['userdata']['email'], $data['userdata']['token_purpose']);
        $status_message = 'Your user account has been successfully confirmed. You can now login.';
        $page = 'register_success';
    } else {
        $page = 'error';
    }

    $data['status_message'] = $status_message;
}

view_page($page, $data);
