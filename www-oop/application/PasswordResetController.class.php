<?php

namespace Devlabs\App;

/**
 * Class PasswordResetController
 * @package Devlabs\App
 */
class PasswordResetController extends AbstractController
{
    /**
     * Default action method for rendering the password reset page logic
     *
     * @return view
     */
    public function index()
    {

        /**
         * Data array for keeping the variables which will be passed to the view
         */
        $data = array();

        /**
         * Logic for processing the page if it is loaded
         * by submitting a 'passwordreset' form
         */
        if (FormHelper::isFormSubmitted('password_reset')) {
            $email = trim($_POST['email']);

            /**
             * Try to load user data into an object by using the inputted email
             */
            $user = new User();
            $user->loadByEmail($email);

            if (UserAuthHelper::validatePasswordResetData($user, $email, $status_message)) {
                // generate new token
                $token = new Token();
                $token->userId = $user->id;
                $token->purpose = 'password_reset';
                $token->value = StringHelper::randomStringAlphanum(30);
                $token->datetime = DateHelper::datetimeToString(time());
                // insert the new token in database
                $token->insert();

                $mail = new Mail();
                $mail->fromEmail = 'sportify@devlabs-projects.com';
                $mail->toEmail = $user->email;
                $mail->subject = 'Sportify - password reset request at ' . DateHelper::datetimeToString(time());
                $url = UrlHelper::getSiteUrl() . '&token=' . $token->value;
                $mail->message = load_view(
                    'html_mail_token_link.php',
                    array('INFORMATIVE_TEXT','URL_TOKEN'),
                    array('Please follow this link to reset your password',$url)
                );
                $mail->send();
            }

            // Store the status message in the data array which will be passed to the view
            $data['status_message'] = $status_message;
        }

        /**
         * Logic for processing the page if it is loaded
         * by receiving a GET request with 'token' variable set
         */
        if (isset($_GET['token']) && !empty($_GET['token'])) {
            /**
             * Load token and user data from the database,
             * based on the token value in the URL query string
             */
            $token = new Token();
            $token->loadByValue($_GET['token']);
            $user = new User();
            $user->loadByToken($token);

            if (UserAuthHelper::validateToken($token, 'password_reset', $status_message)) {
                $data['user'] = $user;
                $data['token'] = $token;
                $this->view = 'passwordchange';
            } else {
                $this->view = 'error';
            }

            $data['status_message'] = $status_message;
        }

        /**
         * Logic for processing the page if it is loaded
         * by submitting a 'passwordchange' form
         */
        if (FormHelper::isFormSubmitted('password_change')) {
            /**
             * Load the user data and token into objects
             * by the email and token value passed in from the from POST parameters
             */
            $user = new User();
            $user->loadByEmail($_POST['email']);
            $token = new Token();
            $token->loadByValue($_POST['token_value']);

            $password = $_POST['password'];
            $passwordConfirm = $_POST['password_confirm'];

            if (UserAuthHelper::validatePasswordData($password, $passwordConfirm, $status_message)) {
                $user->changePassword($password);
                $token->remove();
                header("Location: index.php?page=login");
            }

            $data['status_message'] = $status_message;
        }

        return new view($this->view, $data);
    }
}
