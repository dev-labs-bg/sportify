<?php

define('VIEW_DIR', $_SERVER['DOCUMENT_ROOT'] . '/../views/');

define('POINTS_OUTCOME', 1);
define('POINTS_EXACT', 3);

// set the server timezone to EET (GMT +2)
date_default_timezone_set('EET');

require '../application/helper/UserAuthHelper.class.php';
require '../application/helper/DateHelper.class.php';
require '../application/helper/FormHelper.class.php';
require '../application/helper/StringHelper.class.php';
require '../application/helper/UrlHelper.class.php';
require '../vendor/autoload.php';
require '../config/base.php';
require '../application/model/Database.class.php';
require '../application/model/User.class.php';
require '../application/model/UserCollection.class.php';
require '../application/model/Token.class.php';
require '../application/model/Mail.class.php';
require '../application/model/Tournament.class.php';
require '../application/model/TournamentCollection.class.php';
require '../application/model/MatchCommon.trait.php';
require '../application/model/Match.class.php';
require '../application/model/MatchCollection.class.php';
require '../application/model/Prediction.class.php';
require '../application/model/PredictionCollection.class.php';
require '../application/model/Score.class.php';
require '../application/model/ScoreCollection.class.php';
require '../application/view/View.class.php';
require '../application/controller/Router.class.php';
require '../application/controller/AbstractController.class.php';
require '../application/controller/RegisterController.class.php';
require '../application/controller/LoginController.class.php';
require '../application/controller/LogoutController.class.php';
require '../application/controller/StandingsController.class.php';
require '../application/controller/MatchesController.class.php';
require '../application/controller/TournamentsController.class.php';
require '../application/controller/HistoryController.class.php';
require '../application/controller/ProfileController.class.php';
require '../application/controller/PasswordResetController.class.php';
require '../application/controller/ScoresUpdateController.class.php';

/**
 * Initialize Dotenv for dynamically getting environment variables
 */
$dotenv = new Dotenv\Dotenv(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config');
$dotenv->load();
