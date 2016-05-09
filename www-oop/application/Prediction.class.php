<?php

namespace Devlabs\App;

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
}