<?php

/**
 * Class router
 */
class router
{
	public function set_page()
    {
		return (isset($_GET['page'])) ? $_GET['page'] : 'standings';
	}
}
