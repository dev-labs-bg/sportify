<?php

namespace Devlabs\App;

class RegisterController extends AbstractController
{
    public function index()
    {
        $data = array();
        $user = new User();

        if (isFormSubmitted('register')) {
            $user->email = trim($_POST['email']);
            $user->firstName = $_POST['first_name'];
            $user->lastName = $_POST['last_name'];
            $user->password = $_POST['password'];
            $user->passwordConfirm = $_POST['password_confirm'];

            if (UserAuth::validateRegistration($user, $status_message)) {
                // insert username into database
                $user->add();

                $token = random_string_alphanum(30);
                token_db_insert($email, 'register_confirm', $token);

                $url = getSiteUrl() . '&token=' . $token;
                $from_email = 'sportify@devlabs-projects.com';
                $subject = 'Sportify - user registration request at ' . get_datetime_string(time());
                $message = load_view(
                    'html_mail_token_link.php',
                    array('INFORMATIVE_TEXT','URL_TOKEN'),
                    array('Please follow this link to confirm your registration',$url)
                );
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

        return new view('register', $data);
    }
}
