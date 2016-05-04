<?php

session_start();

require '../include/init.php';
use Mailgun\Mailgun;

/**
 * Initialize database connection
 */
$GLOBALS['db'] = new Devlabs\App\Database();
$GLOBALS['db']->connect(getenv('DB_NAME'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
if (!$GLOBALS['db']->connection) die('Failed to connect to database.');

/**
 * Setup Mailgun for sending e-mails to the users
 */
$http_client = new \Http\Adapter\Guzzle6\Client();
$GLOBALS['mailgun'] = new Mailgun(getenv('MAILGUN_API_KEY'), $http_client);

/**
 * Initialize router and invoke controller
 */
$router = new Devlabs\App\Router();
$controller = $router->getController();
$action = $router->getAction();

/**
 * Create and load the view
 */
$view = $controller->$action();
$view->load();

//echo $response;

//$result = $GLOBALS['db']->query("SELECT * FROM users", array());
//foreach ($result as $row) {
//    echo '<p>' . $row['id'] . '</p>';
//}
