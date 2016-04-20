<?php

$data = new Dataset;
$user = new User;

$data->user['login_status'] = $user->is_logged_in();
$data->page = ( isset($_GET['page']) ? $_GET['page'] : 'standings');

$router = new Router($data);
$data->page = $router->page;

$router->load_controller($data);
$router->view($data);
