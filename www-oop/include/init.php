<?php

define('VIEW_DIR', $_SERVER['DOCUMENT_ROOT'] . '/../views/');

define('POINTS_OUTCOME', 1);
define('POINTS_EXACT', 3);

// set the server timezone to EET (GMT +2)
date_default_timezone_set('EET');

require '../application/UserAuth.class.php';
require '../application/SysHelper.class.php';
require '../vendor/autoload.php';
require '../config/base.php';
require '../application/Database.class.php';
require '../application/User.class.php';
require '../application/Token.class.php';
require '../application/Mail.class.php';
require '../application/Tournament.class.php';
require '../application/TournamentCollection.class.php';
require '../application/Match.class.php';
require '../application/MatchCollection.class.php';
require '../application/Prediction.class.php';
require '../application/PredictionCollection.class.php';
require '../application/Score.class.php';
require '../application/ScoreCollection.class.php';
require '../application/Router.class.php';
require '../application/View.class.php';
require '../application/AbstractController.class.php';
require '../application/RegisterController.class.php';
require '../application/LoginController.class.php';
require '../application/LogoutController.class.php';
require '../application/StandingsController.class.php';
require '../application/MatchesController.class.php';
require '../application/TournamentsController.class.php';
require '../application/HistoryController.class.php';

/**
 * Initialize Dotenv for dynamically getting environment variables
 */
$dotenv = new Dotenv\Dotenv(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config');
$dotenv->load();

function load_view($view_file, $str_search, $str_replace)
{
    $filepath = VIEW_DIR . $view_file;
    $view_data = file_get_contents($filepath);

    return str_replace($str_search, $str_replace, $view_data);
}
