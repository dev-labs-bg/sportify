<?php

$data = array();

if ( is_form_submitted('password_reset') ) {

    $email = trim($_POST['email']);

    if ( validate_password_reset_input($email, $status_message) ) {
        // TODO - function for unique token?
        $token = random_string_alphanum(30);
        token_db_insert($email, 'password_reset', $token);

        $url = get_site_url() . '&token=' . $token;
        $from_email = 'ceco@devlabs.bg';
        $subject = 'Sportify - password reset';
        $message = load_view('html_mail_password_reset.php', 'URL_RESET', $url);
        send_mail($email, $from_email, $subject, $message);
    }

    $data['status_message'] = $status_message;
}

if ( is_form_submitted('password_change') ) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ( validate_password ($password, $password_confirm, $status_message) ) {
        change_password($email, $password);
        clear_token($email, $_POST['token_purpose']);
        header("Location: index.php?page=login");
    }

    $data['status_message'] = $status_message;
}

if ( isset($_GET['token']) && !empty($_GET['token']) ) {
    $data['userdata'] = get_userdata_by_token($_GET['token']);

    if ( validate_userdata_token($data['userdata'], $status_message) ) {
        $page = 'password_change';
    } else {
        $page = 'error';
    }

    $data['status_message'] = $status_message;
}

view_page($page, $data);
