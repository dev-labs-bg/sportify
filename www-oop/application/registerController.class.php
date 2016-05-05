<?php

namespace Devlabs\App;

/**
 * Class RegisterController
 * @package Devlabs\App
 */
class RegisterController extends AbstractController
{
    /**
     * Default action method for rendering the register page logic
     *
     * @return view
     */
    public function index()
    {
        /**
         * Data array for keeping the variables which will be passed to the view
         */
        $data = array();

        $user = new User();

        if (SysHelper::isFormSubmitted('register')) {
            $user->email = trim($_POST['email']);
            $user->firstName = $_POST['first_name'];
            $user->lastName = $_POST['last_name'];
            $user->password = $_POST['password'];
            $user->passwordConfirm = $_POST['password_confirm'];

            if (UserAuth::validateRegistrationData($user, $status_message)) {
                // insert username into database
                $user->insert();

                // generate new token
                $token = new Token();
                $token->userId = $user->id;
                $token->purpose = 'register_confirm';
                $token->value = SysHelper::randomStringAlphanum(30);
                $token->datetime = SysHelper::datetimeToString(time());
                // insert the new token in database
                $token->insert();

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

        if (isset($_GET['token']) && !empty($_GET['token'])) {
            $token = new Token();
            $token->loadByValue($_GET['token']);
            $user->loadByToken($token);

            if (UserAuth::validateToken($token, 'register_confirm', $status_message)) {
                $user->setConfirmed();
                $token->remove();
                $status_message = 'Your user account has been successfully confirmed. You can now login.';
                $this->view = 'register_success';
            } else {
                $this->view = 'error';
            }

            $data['status_message'] = $status_message;
        }

        return new view($this->view, $data);
    }
}
