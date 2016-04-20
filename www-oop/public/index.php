<?php

$user = new User;

$data['login_status'] = UserAuth::is_logged_in();
$data['page'] = (isset($_GET['page']) ? $_GET['page'] : 'standings');

$router = new Router();
$router->load_controller($data);
