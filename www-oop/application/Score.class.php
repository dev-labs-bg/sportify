<?php

namespace Devlabs\App;

/**
 * Class Score
 * @package Devlabs\App
 */
class Score
{
    public $id;
    public $userId;
    public $email;
    public $tournamentId;
    public $points;

    public function __construct($id, $user_id, $email, $tournament_id, $points)
    {
        $this->id = $id;
        $this->userId = $user_id;
        $this->email = $email;
        $this->tournamentId = $tournament_id;
        $this->points = $points;
    }
}