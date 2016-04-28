<?php

$user = new User;

$data['login_status'] = UserAuth::is_logged_in();

$router = new router();
$data['page'] = $router->set_page();

$controller = new ${data['page']}Controller();
