<?php

/**
 * Class router
 */
class router
{
	public function get_controller()
    {
        $controller = (isset($_GET['page'])) ? $_GET['page'] : 'standings';
        $controllerClass = $controller . 'Controller';

		return new $controllerClass($controller, false);
	}

    public function get_action()
    {
        return (isset($_GET['action'])) ? $_GET['action'] : 'index';
    }
}
