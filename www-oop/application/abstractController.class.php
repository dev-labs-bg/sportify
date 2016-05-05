<?php

namespace Devlabs\App;

/**
 * Class ControllerAbstract
 */
abstract class AbstractController
{

    private $all_access_pages = array(
        'login',
        'register',
        'password_reset',
        'password_change',
        'standings',
    );

    public function __construct($controller, $loginStatus)
    {
        if ( !$loginStatus && !in_array($controller,$this->all_access_pages) ) {
            header("Location: index.php?page=login");
        }
    }
}
