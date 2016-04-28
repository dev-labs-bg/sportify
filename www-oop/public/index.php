<?php

session_start();

//$user = new User;

//$data['login_status'] = UserAuth::is_logged_in();

require '../vendor/autoload.php';
require '../config/base.php';
require '../application/database.class.php';
require '../application/router.class.php';
require '../application/abstractController.class.php';
require '../application/registerController.class.php';
use Mailgun\Mailgun;

function get_home_url() {
    return 'http://' . $_SERVER['HTTP_HOST'];
}

$dotenv = new Dotenv\Dotenv(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config');
$dotenv->load();

$GLOBALS['db'] = new devlabs\app\database();
$GLOBALS['db']->connect(getenv('DB_NAME'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
if (!$GLOBALS['db']->connection) die('Failed to connect to database.');

$http_client = new \Http\Adapter\Guzzle6\Client();
$GLOBALS['mailgun'] = new Mailgun(getenv('MAILGUN_API_KEY'), $http_client);

$router = new router();
$controller = $router->get_controller();
$action = $router->get_action();

$response = $controller->$action();

echo $response;

$query = $GLOBALS['db']->query("SELECT * FROM users", array());
echo $query->fetchAll();
