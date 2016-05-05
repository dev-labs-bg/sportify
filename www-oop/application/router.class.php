<?php

namespace Devlabs\App;

/**
 * Class router
 */
class Router
{
    /**
     * Property for keeping the controller to be invoked
     *
     * @var string
     */
    private $controller;

    /**
     * Property for keeping the controller's action
     *
     * @var string
     */
    private $action;

    /**
     * Set the $controller and $action properties on instantiation
     *
     */
    public function __construct()
    {
        $this->controller = (isset($_GET['page'])) ? $_GET['page'] : 'standings';
        $this->action = (isset($_GET['action'])) ? $_GET['action'] : 'index';
    }

    /**
     * Construct the controller class name and create a new instance of it
     *
     * @return object
     */
    public function getController()
    {
        $controllerClass = 'devlabs\app\\' . $this->controller . 'Controller';

		return new $controllerClass($this->controller, userAuth::getLoginStatus());
	}

    /**
     * Get and return the action method to be passed to the controller
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
