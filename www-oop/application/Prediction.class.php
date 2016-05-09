<?php

namespace Devlabs\App;

/**
 * Class Prediction
 * @package Devlabs\App
 */
class Prediction
{
    public $id;
    public $matchId;
    public $userId;
    public $homeGoals;
    public $awayGoals;
    public $points;
    public $scoreAdded;

    public function __construct($id = '', $matchId = '', $userId = '',
                                $homeGoals = '', $awayGoals = '', $points = '', $scoreAdded = '')
    {
        $this->id = $id;
        $this->matchId = $matchId;
        $this->userId = $userId;
        $this->homeGoals = $homeGoals;
        $this->awayGoals = $awayGoals;
        $this->points = $points;
        $this->scoreAdded = $scoreAdded;
    }

    public function loadById(User $user, $id)
    {
        $query = $GLOBALS['db']->query(
            "SELECT * FROM predictions WHERE match_id = :id AND user_id = :user_id",
            array('id' => $id, 'user_id' => $user->id)
        );

        if ($query) {
            $this->id = $query[0]['id'];
            $this->matchId = $query[0]['match_id'];
            $this->userId = $query[0]['user_id'];
            $this->homeGoals = $query[0]['home_goals'];
            $this->awayGoals = $query[0]['away_goals'];
            $this->points = $query[0]['points'];
            $this->scoreAdded = $query[0]['score_added'];
        }
    }

    public function makePrediction(User $user, $matchId, $homeGoals, $awayGoals)
    {

    }

    public function updatePrediction(User $user, $matchId, $homeGoals, $awayGoals)
    {

    }

    public function insertPrediction(User $user, $matchId, $homeGoals, $awayGoals)
    {

    }
}