<?php

//$user = new User;

//$data['login_status'] = UserAuth::is_logged_in();

require '../application/router.class.php';
require '../application/controller.class.php';

$router = new router();
$controller = $router->get_controller();
$action = $router->get_action();

$response = $controller->$action();

//echo $response;