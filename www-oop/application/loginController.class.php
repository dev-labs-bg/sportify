<?php

namespace Devlabs\App;

/**
 * Class loginController
 * @package devlabs\app
 */
class LoginController extends AbstractController
{
    /**
     * Default action method for rendering the login page logic
     *
     * @return view
     */
    public function index()
    {
        /**
         * Data array which will be passed to the view
         */
        $data = array();

        /**
         * Redirect to home page if user is already logged in and tries to access the login page
         */
        if (UserAuth::getLoginStatus()) {
            header("Location: index.php");
        }

        /**
         * Check if 'form_name' with value 'login' has been submitted
         */
        if (isFormSubmitted('login')) {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            /**
             * If user input's e-mail and password are valid,
             * setup user's session and redirect to the home page
             */
            if (UserAuth::validateLogin($email, $password, $status_message)) {
                UserAuth::setLogin($email);
                header("Location: index.php");
            }

            /**
             * Store the status of the login attempt in the data array
             */
            $data = array('status_message' => $status_message);
        }

        return new View('login', $data);
    }
}
