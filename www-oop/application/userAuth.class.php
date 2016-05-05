<?php

namespace Devlabs\App;

/**
 * Class userAuth
 * @package devlabs\app
 */
class UserAuth
{
    /**
     * Check user is logged in
     *
     * @return bool
     */
    public static function getLoginStatus()
    {
        return isset($_SESSION['email']);
    }

    /**
     * Set login in the _SESSION variable
     *
     * @param $email
     */
    public static function setLogin($email)
    {
        $_SESSION['email'] = $email;
    }

    /**
     * Unset login in the _SESSION variable
     */
    public static function unsetLogin()
    {
        unset($_SESSION['email']);
        session_destroy();
    }


    public static function validateLogin(User $user, &$status_message = null)
    {
        $is_data_invalid = (empty($user->email) || empty($user->password));

        if ($is_data_invalid) {
            $status_message = 'Please provide both email and password.';

            return false;
        } else if (!$user->checkCredentials()) {
            $status_message = 'Incorrect username or password.';

            return false;
        } else {
            $status_message = 'Thank you for logging in.';
        }

        return true;
    }

    public static function validateRegistration(User $user, &$status_message = null) {
        $is_data_invalid = (
            empty($user->email) || empty($user->firstName) || empty($user->lastName) || empty($user->password) ||
            empty($user->passwordConfirm) || ! self::validEmail($user->email) ||
            ($user->password !== $user->passwordConfirm)
        );

        if ($is_data_invalid) {
            $status_message = 'Please provide a first and last names, valid email address and type in same password twice.';

            return false;
        } else if ($user->lookup()) {
            $status_message = 'Email already used. Please provide different email address.';

            return false;
        } else {
            $status_message = 'Thank you for registering. A confirmation e-mail was sent to you.';
        }

        return true;
    }

    public static function validEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}