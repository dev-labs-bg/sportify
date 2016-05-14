<?php

namespace Devlabs\App;

/**
 * Class StandingsController
 * @package Devlabs\App
 */
class StandingsController extends AbstractController
{
    /**
     * Default action method for rendering the standings logic
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
         * Get the tournament id from the URL query string
         * and load the tournament's data into an object
         */
        $tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : "5";
        $tournament = new Tournament();
        $tournament->loadById($tournament_id);

        /**
         * Create objects for holding the tournaments and tables' standings data
         */
        $tournaments = new TournamentCollection();
        $standings = new ScoreCollection();

        /**
         * Store the tournaments and tables' standings data
         * in the data array which will be passed to the view
         */
        $data['selected_tournament'] = $tournament;
        $data['tournaments'] = $tournaments->getAll($tournament->id);
        $data['standings'] = $standings->getByTournament($tournament);

        return new view($this->view, $data);
    }
}
