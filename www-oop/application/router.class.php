<?php

/**
 * Class Router
 */
class Router
{
	public function load_controller($data)
    {
		require (CTRL_DIR . $data['page'] . '.php');
	}
}
