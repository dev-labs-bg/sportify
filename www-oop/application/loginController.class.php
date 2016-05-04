<?php

namespace devlabs\app;

/**
 * Class loginController
 * @package devlabs\app
 */
class loginController extends abstractController
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
         * Check if 'form_name' with value 'login' has been submitted
         */
        if (isFormSubmitted('login')) {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            /**
             * If user input's e-mail and password are valid,
             * setup user's session and redirect to the home page
             */
            if (userAuth::validateLogin($email, $password, $login_status)) {
                userAuth::setLogin($email);
                header("Location: index.php");
            }

            /**
             * Store the status of the login attempt in the data array
             */
            $data = array('login_status' => $login_status);
        }

        return new view('login', $data);
    }
}
