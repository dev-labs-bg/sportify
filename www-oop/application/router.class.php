<?php

namespace devlabs\app;

/**
 * Class router
 */
class router
{
    /**
     *
     * @return mixed
     */
    public function getController()
    {
        $controller = (isset($_GET['page'])) ? $_GET['page'] : 'standings';
        $controllerClass = 'devlabs\app\\' . $controller . 'Controller';

		return new $controllerClass($controller, userAuth::loginStatus());
	}

    /**
     * @return string
     */
    public function getAction()
    {
        return (isset($_GET['action'])) ? $_GET['action'] : 'index';
    }
}
