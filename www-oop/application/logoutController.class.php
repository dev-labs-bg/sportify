<?php

namespace Devlabs\App;

/**
 * Class logoutController
 * @package devlabs\app
 */
class LogoutController extends AbstractController
{
    /**
     * Default action method for the logout logic
     *
     * @return view
     */
    public function index()
    {
        /**
         * Destroy the user session and redirect to the home page
         */
        userAuth::unsetLogin();
        header("Location: index.php");
    }
}
