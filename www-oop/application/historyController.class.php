<?php

namespace Devlabs\App;

/**
 * Class HistoryController
 * @package Devlabs\App
 */
class HistoryController extends AbstractController
{
    /**
     * Default action method for rendering the history page logic
     *
     * @return view
     */
    public function index()
    {

        /**
         * Data array for keeping the variables which will be passed to the view
         */
        $data = array();

        /**
         * Get the email value from the URL query string
         * or if not set, set it to the current user
         * and load user data based on this email value
         */
        $email = (isset($_GET['username'])) ? $_GET['username'] : $_SESSION['email'];
        $user = new User();
        $user->loadByEmail($email);

        /**
         * Initialize the dateFrom and dateTo variables,
         * based on the values of date_from and date_to from the URL query string
         */
        $dateFrom = DateHelper::setDateStart($_GET['date_from'], '2016-03-31');
        $dateTo = DateHelper::setDateEnd($_GET['date_to'], 86400);

        /**
         * Get the tournament id from the URL query string
         */
        $tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : 'ALL';

        /**
         * Create objects for holding the tournaments, matches, predictions and users data
         */
        $tournaments = new TournamentCollection();
        $matches = new MatchCollection();
        $predictions = new PredictionCollection();
        $users = new UserCollection();

        /**
         * Store the joined tournaments, matches and prediction data
         * in the data array which will be passed to the view
         */
        $data['tournaments_joined'] = $tournaments->getJoined($user, $tournament_id);
        $data['matches'] = $matches->getAlreadyScored($user, $tournament_id, $dateFrom, $dateTo);
        $data['predictions'] = $predictions->getAlreadyScored($user, $tournament_id, $dateFrom, $dateTo);
        $data['users'] = $users->getAllConfirmed($email);

        return new view($this->view, $data);
    }
}
