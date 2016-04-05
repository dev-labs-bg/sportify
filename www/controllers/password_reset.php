<?php

$data = array();

if ( isset($_POST['form_name']) && $_POST['form_name'] === 'password_reset' ) {

    $email = trim($_POST['email']);

    if ( validate_reset_input($email, $password_reset_status) ) {
        $token = random_string_alphanum(30);
        token_db_insert($email, 'password_reset', $token);

        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&token=' . $token;
        $from_email = 'ceco@devlabs.bg';
        $subject = 'Sportify - password reset';
        $message = '<html>
                    <head></head>
                    <body>
                        Please click this link to reset your password: <a href="' . $url . '">Reset password</a>
                    </body>
                    </html>';
        send_mail($email, $from_email, $subject, $message);
    }

    $data = array('password_reset_status' => $password_reset_status);
}

if ( isset($_GET['token']) && !empty($_GET['token']) ) {
    $data['userdata'] = get_userdata_by_token($_GET['token']);

    if ( validate_userdata_token($data['userdata'], $password_reset_status) ) {

        $page = 'password_change';
        view_page($page, $data);
    }

    header("Location: index.php");
}

view_page($page, $data);
