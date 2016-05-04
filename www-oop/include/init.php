<?php

define('VIEW_DIR', $_SERVER['DOCUMENT_ROOT'] . '/../views/');

define('POINTS_OUTCOME', 1);
define('POINTS_EXACT', 3);

require '../vendor/autoload.php';
require '../config/base.php';
require '../application/database.class.php';
require '../application/router.class.php';
require '../application/abstractController.class.php';
require '../application/registerController.class.php';
require '../application/loginController.class.php';
require '../application/logoutController.class.php';
require '../application/standingsController.class.php';
require '../application/matchesController.class.php';
require '../application/tournamentsController.class.php';
require '../application/historyController.class.php';
require '../application/view.class.php';
require '../application/userAuth.class.php';

/**
 * Initialize Dotenv for dynamically getting environment variables
 */
$dotenv = new Dotenv\Dotenv(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config');
$dotenv->load();

function form_prev_value($item)
{
    if ( !empty($_POST[$item]) ) {
        return htmlspecialchars($_POST[$item]);
    }

    return '';
}

function isFormSubmitted($form_name)
{
    return isset($_POST['form_name']) && $_POST['form_name'] === $form_name;
}
