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
         * Check if 'form_name' with value 'passwordreset' has been submitted
         */
        if (SysHelper::isFormSubmitted('passwordreset')) {
            $email = trim($_POST['email']);

            /**
             * Try to load user data into an object by using the inputted email
             */
            $user = new User();
            $user->loadByEmail($email);

            if (UserAuth::validatePasswordResetData($user, $email, $status_message)) {
                // generate new token
                $token = new Token();
                $token->userId = $user->id;
                $token->purpose = 'register_confirm';
                $token->value = SysHelper::randomStringAlphanum(30);
                $token->datetime = SysHelper::datetimeToString(time());
                // insert the new token in database
                $token->insert();

                $mail = new Mail();
                $mail->fromEmail = 'sportify@devlabs-projects.com';
                $mail->toEmail = $user->email;
                $mail->subject = 'Sportify - password reset request at ' . SysHelper::datetimeToString(time());
                $url = SysHelper::getSiteUrl() . '&token=' . $token->value;
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

        if (SysHelper::isFormSubmitted('passwordchange')) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['password_confirm'];

            /**
            * Load user data into an object by using the inputted email
            */
            $user = new User();
            $user->loadByEmail($email);

            if (UserAuth::validatePasswordData($password, $passwordConfirm, $status_message)) {
                $user->changePassword($password);
                $token->remove();
                    // clear_token($email, $_POST['token_purpose']);
                header("Location: index.php?page=login");
            }

            $data['status_message'] = $status_message;
        }

        return new view($this->view, $data);
    }
}
