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

        /**
         * Check if 'form_name' with value 'register' has been submitted
         */
        if (FormHelper::isFormSubmitted('register')) {
            $user->email = trim($_POST['email']);
            $user->firstName = $_POST['first_name'];
            $user->lastName = $_POST['last_name'];
            $user->password = $_POST['password'];
            $user->passwordConfirm = $_POST['password_confirm'];

            /**
             * Check if the inputted user data is valid for registration
             */
            if (UserAuthHelper::validateRegistrationData($user, $status_message)) {
                // insert username into database
                $user->insert();

                // generate new token
                $token = new Token();
                $token->userId = $user->id;
                $token->purpose = 'register_confirm';
                $token->value = StringHelper::randomStringAlphanum(30);
                $token->datetime = DateHelper::datetimeToString(time());
                // insert the new token in database
                $token->insert();

                $mail = new Mail();
                $mail->fromEmail = 'sportify@devlabs-projects.com';
                $mail->toEmail = $user->email;
                $mail->subject = 'Sportify - user registration request at ' . DateHelper::datetimeToString(time());
                $url = UrlHelper::getSiteUrl() . '&token=' . $token->value;
                $mail->message = View::loadTemplate(
                    'html_mail_token_link.php',
                    array('INFORMATIVE_TEXT','URL_TOKEN'),
                    array('Please follow this link to confirm your registration',$url)
                );
                $mail->send();
            }

            /**
             * Store the status message in the data array which will be passed to the view
             */
            $data['status_message'] = $status_message;
        }

        /**
         * Check if there is a token item in the URL query string
         */
        if (isset($_GET['token']) && !empty($_GET['token'])) {
            $token = new Token();
            $token->loadByValue($_GET['token']);

            /**
             * Check if the token in the query string is valid
             */
            if (UserAuthHelper::validateToken($token, 'register_confirm', $status_message)) {
                /**
                 * Load user data by passing in token,
                 * set the user as confirmed in the DB,
                 * and remove the token from the DB
                 */
                $user->loadByToken($token);
                $user->setConfirmed();
                $token->remove();

                $this->view = 'register_success';
            } else {
                $this->view = 'error';
            }

            /**
             * Store the status message in the data array which will be passed to the view
             */
            $data['status_message'] = $status_message;
        }

        return new view($this->view, $data);
    }
}
