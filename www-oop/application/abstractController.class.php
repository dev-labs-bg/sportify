<?php

namespace Devlabs\App;

/**
 * Class ControllerAbstract
 */
abstract class AbstractController
{
    protected $view;
    private $allAccessViews = array(
        'login',
        'register',
        'passwordreset',
        'passwordchange',
        'standings',
    );

    public function __construct($controller, $loginStatus)
    {
        if (!$loginStatus && !in_array($controller,$this->allAccessViews)) {
            header("Location: index.php?page=login");
        }

        $this->view = $controller;
    }
}
