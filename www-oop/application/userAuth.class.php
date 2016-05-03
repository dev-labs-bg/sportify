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
    }
}