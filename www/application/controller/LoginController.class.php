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
         * Data array for keeping the variables which will be passed to the view
         */
        $data = array();

        $user = new User();

        /**
         * Redirect to home page if user is already logged in and tries to access the login page
         */
        if (UserAuthHelper::getLoginStatus()) {
            header('Location: index.php');
        }

        /**
         * Check if 'form_name' with value 'login' has been submitted
         */
        if (FormHelper::isFormSubmitted('login')) {
            $user->email = trim($_POST['email']);
            $user->password = $_POST['password'];

            /**
             * If user input's e-mail and password are valid,
             * setup user's session and redirect to the home page
             */
            if (UserAuthHelper::validateLoginData($user, $status_message)) {
                UserAuthHelper::setLogin($user->email);

                // Load full user data from the database
                $user->loadByEmail($user->email);

                /**
                 * Get the tournaments the user has joined in,
                 * if NONE - redirect to the tournaments page
                 * else - redirect to the home page
                 */
                $tournaments = new TournamentCollection();
                if (count($tournaments->getJoined($user)) == 0) {
                    header('Location: index.php?page=tournaments');
                } else {
                    header('Location: index.php');
                }
            }

            /**
             * Store the status of the login attempt in the data array
             */
            $data = array('status_message' => $status_message);
        }

        return new View($this->view, $data);
    }
}
