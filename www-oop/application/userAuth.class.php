<?php

namespace Devlabs\App;

/**
 * Class UserAuth
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

    /**
     * Method for validating user's input data for login
     *
     * @param User $user
     * @param null $status_message
     * @return bool
     */
    public static function validateLoginData(User $user, &$status_message = null)
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

    /**
     * Method for validating user's input data for registration
     *
     * @param User $user
     * @param null $status_message
     * @return bool
     */
    public static function validateRegistrationData(User $user, &$status_message = null)
    {
        $is_data_invalid = (
            empty($user->email) || empty($user->firstName) || empty($user->lastName) || empty($user->password) ||
            empty($user->passwordConfirm) || !self::isEmailValid($user->email) ||
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

    /**
     * Check if input string is valid e-mail address
     *
     * @param string $email
     * @return mixed
     */
    public static function isEmailValid($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check if a given token is valid
     *
     * @param Token $token
     * @param $purpose
     * @param null $status_message
     * @return bool
     */
    public static function validateToken(Token $token, $purpose, &$status_message = null)
    {
        if (empty($token->id) || !isset($token->id) || $token->purpose !== $purpose) {
            $status_message = 'Invalid token ID.';

            return false;
        } else if (!$token->checkValidity()) {
            $status_message = 'Token ID has expired.';

            return false;
        } else {
            $status_message = 'Your user account has been successfully confirmed. You can now login.';
        }

        return true;
    }

    /**
     * Check if user input password and passwordConfirm are valid
     *
     * @param $password
     * @param $passwordConfirm
     * @param null $status_message
     * @return bool
     */
    public static function validatePasswordData($password, $passwordConfirm, &$status_message = null)
    {
        if ( empty($password) || empty($passwordConfirm) || $password !== $passwordConfirm ) {
            $status_message = 'Please type in same password twice.';

            return false;
        } else {
            $status_message = 'You have successfully changed your profile details.';
        }

        return true;
    }
}