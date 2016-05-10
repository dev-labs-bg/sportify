<?php

namespace Devlabs\App;

class MatchesController extends AbstractController
{
    public function index()
    {
        /**
         * Data array for keeping the variables which will be passed to the view
         */
        $data = array();

        /**
         * Load the data for the current user into an object
         */
        $user = new User();
        $user->loadByEmail($_SESSION['email']);

        // if the page is submitted via a single match BET/UPDATE button
        if (isset($_POST['match_id'])) {
            $match = new Match();
            $match->loadById($_POST['match_id']);

            $homeGoals = $_POST['home_goals'];
            $awayGoals = $_POST['away_goals'];

            $isPredictionValid = Prediction::validateData($match, $homeGoals, $awayGoals, $status_message);

            if ($isPredictionValid) {
                $prediction = new Prediction();
                $prediction->makePrediction($user, $match, $homeGoals. $awayGoals);
            }

            $data['prediction_value'] = $isPredictionValid;
            $data['prediction_status'] = $status_message;
            $data['match_id'] = $match->id;
        }

        // if the page is submitted via the BET/UPDATE ALL button
        if (isset($_POST['matches'])) {
            foreach ($_POST['matches'] as $row) {
                $match = new Match();
                $match->loadById($row['match_id']);

                $homeGoals = $row['home_goals'];
                $awayGoals = $row['away_goals'];

                $isPredictionValid = Prediction::validateData($match, $homeGoals, $awayGoals, $status_message);

                if ($isPredictionValid) {
                    $prediction = new Prediction();
                    $prediction->makePrediction($user, $match, $homeGoals. $awayGoals);
                }
            }
        }

        $dateFrom = SysHelper::setDateStart($_GET['date_from'], date("Y-m-d"));
        $dateTo = SysHelper::setDateEnd($_GET['date_to'], 86400);

        /**
         * Get the tournament id from the URL query string
         */
        $tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : "ALL";

        /**
         * Create objects for holding the tournaments, matches and predictions data
         */
        $tournaments = new TournamentCollection();
        $matches = new MatchCollection();
        $predictions = new PredictionCollection();

        $data['tournaments_joined'] = $tournaments->getJoined($user, $tournament_id);
        $data['matches'] = $matches->getNotScored($user, $tournament_id, $dateFrom, $dateTo);
        $data['predictions'] = $predictions->getNotScored($user, $tournament_id, $dateFrom, $dateTo);

        return new view($this->view, $data);
    }
}
