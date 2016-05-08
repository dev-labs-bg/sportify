<?php

namespace Devlabs\App;

class ScoreCollection
{
    public $table = array();

    public function getByTournament(Tournament $tournament)
    {
        $this->table = array();

        $query = $GLOBALS['db']->query(
            "SELECT users.email, scores.id, scores.user_id, scores.tournament_id, scores.points
                FROM scores
                INNER JOIN users ON users.id = scores.user_id
                WHERE scores.tournament_id = :tournament_id
                ORDER BY scores.points DESC, users.email ASC",
            array('tournament_id' => $tournament->id)
        );

        if ($query) {
            foreach ($query as $row) {
                $this->table[] = new Score($row['id'], $row['user_id'], $row['email'], $row['tournament_id'], $row['points']);
            }
        }

        return $this->table;
    }

    public function getByUser(User $user)
    {
        $this->table = array();

        $query = $GLOBALS['db']->query(
            "SELECT users.email, scores.id, scores.user_id, scores.tournament_id, scores.points
                FROM scores
                INNER JOIN users ON users.id = scores.user_id
                WHERE scores.user_id = :user_id",
            array('user_id' => $user->id)
        );

        if ($query) {
            foreach ($query as $row) {
                $this->table[] = new Score($row['id'], $row['user_id'], $row['email'], $row['tournament_id'], $row['points']);
            }
        }

        return $this->table;
    }
}