<?php

namespace Devlabs\App;

class TournamentCollection
{
    public $joined = array();
    public $available = array();
    public $all = array();

    public function getJoined(User $user, $tournament_id = "ALL")
    {
        $this->joined = array();

        $query = $GLOBALS['db']->query(
            "SELECT tournaments.id, tournaments.name, tournaments.start, tournaments.end
                FROM scores
                INNER JOIN users ON users.id = scores.user_id
                INNER JOIN tournaments ON tournaments.id = scores.tournament_id
                WHERE users.email = :email",
            array('email' => $user->email)
        );

        if ($query) {
            // set selected flag for selected tournament
            foreach ($query as &$row) {
                if ( $row['id'] == $tournament_id ) {
                    $row['selected'] = 'selected';
                } else {
                    $row['selected'] = '';
                }

                $this->joined[] = new Tournament($row['id'], $row['name'], $row['start'], $row['end'], $row['selected']);
            }
        }

        return $this->joined;
    }

    public function getAvailable(User $user, $tournament_id = "ALL")
    {
        $this->available = array();

        $query = $GLOBALS['db']->query(
            "SELECT tournaments.id, tournaments.name, tournaments.start, tournaments.end
                FROM tournaments
                WHERE tournaments.id NOT IN
                    (SELECT scores.tournament_id
                    FROM scores
                    INNER JOIN users ON users.id = scores.user_id
                    WHERE users.email = :email)",
            array('email' => $user->email)
        );

        if ($query) {
            // set selected flag for selected tournament
            foreach ($query as &$row) {
                if ( $row['id'] == $tournament_id ) {
                    $row['selected'] = 'selected';
                } else {
                    $row['selected'] = '';
                }

                $this->available[] = new Tournament($row['id'], $row['name'], $row['start'], $row['end'], $row['selected']);
            }
        }

        return $this->available;
    }

    public function getAll($tournament_id = "ALL")
    {
        $this->all = array();

        $query = $GLOBALS['db']->query(
            "SELECT * FROM tournaments",
            array()
        );

        if ($query) {
            // set selected flag for selected tournament
            foreach ($query as &$row) {
                if ( $row['id'] == $tournament_id ) {
                    $row['selected'] = 'selected';
                } else {
                    $row['selected'] = '';
                }

                $this->all[] = new Tournament($row['id'], $row['name'], $row['start'], $row['end'], $row['selected']);
            }
        }

        return $this->all;
    }

    public function join(User $user, $tournaments)
    {
        foreach ($tournaments as $tournament_id) {
            $query = $GLOBALS['db']->query(
                "INSERT IGNORE INTO scores(user_id,tournament_id,points)
                    VALUES(:user_id, :tournament_id, 0)",
                array('user_id' => $user->id, 'tournament_id' => $tournament_id)
            );
        }
    }

    public function leave(User $user, $tournaments)
    {
        foreach ($tournaments as $tournament_id) {
            $query = $GLOBALS['db']->query(
                "DELETE FROM scores
                    WHERE user_id = :user_id AND tournament_id = :tournament_id",
                array('user_id' => $user->id, 'tournament_id' => $tournament_id)
            );
        }
    }
}