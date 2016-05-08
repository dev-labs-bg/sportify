<?php

namespace Devlabs\App;

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

        $user = new User();
        $user->loadByEmail($_SESSION['email']);

        $tournament_id = (isset($_GET['tournament_id'])) ? $_GET['tournament_id'] : "5";
        $tournament = new Tournament();
        $tournament->loadById($tournament_id);

//        $data['tournaments'] = list_all_tournaments($tournament_id);
//        $data['standings'] = list_standings($tournament_id);
//        $data['tournament_name'] = get_tournament_name($tournament_id);

        $tournaments = new TournamentCollection();
        $standings = new ScoreCollection();

        $data['tournament'] = $tournament;
        $data['tournaments'] = $tournaments->getAll($tournament->id);
        $data['standings'] = $standings->getByTournament($tournament);

        return new view($this->view, $data);
    }
}
