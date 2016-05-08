<?php

namespace Devlabs\App;

class Score
{
    public $id;
    public $userId;
    public $tournamentId;
    public $points;

    public function __construct($id, $user_id, $tournament_id, $points)
    {
        $this->id = $id;
        $this->userId = $user_id;
        $this->tournamentId = $tournament_id;
        $this->points = $points;
    }
}