<?php

class Router {
	public $page;
	private $all_access_pages = array(
		'login', 'register', 'password_reset','password_change', 'standings');

	function __construct ($data) {
		if ( !$data->user['login_status'] && !in_array($data->page,$all_access_pages) ) {
	    	$this->page = 'login';
		} else {
			$this->page = $data->page;
		}
	}

	public function load_controller($data) {
		require (CTRL_DIR . "${this->page}.php");
	}

	public function view($data) {
		include VIEW_DIR . "header.php";
		include VIEW_DIR . "{$this->page}.view.php";
		include VIEW_DIR . "footer.php";
	}
}
