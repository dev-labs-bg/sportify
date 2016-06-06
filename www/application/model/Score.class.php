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
    }s

    /**
     * Method for updating the user's points in a tournament
     * by passing the points to be added as an argument
     *
     * @param $addedPoints
     * @return mixed
     */
    public function updatePoints($addedPoints)
    {
        return $GLOBALS['db']->query(
            "UPDATE scores
            SET points = points + :added_points
            WHERE user_id = :user_id AND tournament_id = :tournament_id",
            array(
                'user_id' => $this->userId,
                'tournament_id' => $this->tournamentId,
                'added_points' => $addedPoints
            )
        );
    }
}