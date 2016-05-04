<?php

namespace devlabs\app;

/**
 * Class userAuth
 * @package devlabs\app
 */
class userAuth
{
    /**
     * Check user is logged in
     *
     * @return bool
     */
    public static function loginStatus() {
        return isset($_SESSION['email']);
    }

    /**
     * Set login in the _SESSION variable
     *
     * @param $email
     */
    public static function setLogin($email) {
        $_SESSION['email'] = $email;
    }

    /**
     * Unset login in the _SESSION variable
     */
    public static function unsetLogin() {
        unset($_SESSION['email']);
        session_destroy();
    }


    public static function validateLogin($email, $password, &$login_status = null)
    {
        $is_data_invalid = (empty($email) || empty($password));

        if ($is_data_invalid) {
            $login_status = 'Please provide both email and password.';

            return false;
        } else if (!self::checkUsernamePassword($email, $password)) {
            $login_status = 'Incorrect username or password.';

            return false;
        } else {
            $login_status = 'Thank you for logging in.';
        }

        return true;
    }

    public static function checkUsernamePassword($email, $password)
    {
        $query = $GLOBALS['db']->query(
            "SELECT * FROM users WHERE email = :email AND confirmed = 1",
            array('email' => $email)
        );

        if ($query) {
            $password_hash = $query[0]['password_hash'];

            return password_verify($password, $password_hash);
        } else {
            return false;
        }
    }
}