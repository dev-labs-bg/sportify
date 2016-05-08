<?php

namespace Devlabs\App;

/**
 * Class TournamentCollection
 * @package Devlabs\App
 */
class TournamentCollection
{
    public $joined = array();
    public $available = array();
    public $all = array();

    /**
     * Method for getting a list of the tournaments the current user has joined
     *
     * @param User $user
     * @param string $tournament_id
     * @return array
     */
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

    /**
     * Method for getting a list of the tournaments the current user has NOT yet joined
     *
     * @param User $user
     * @param string $tournament_id
     * @return array
     */
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

    /**
     * Method for getting a list of all the tournaments in the database
     *
     * @param string $tournament_id
     * @return array
     */
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

    /**
     * Method for joining a tournament,
     * by inserting a new row containing the user's id and tournament id into the Scores table
     *
     * @param User $user
     * @param $tournaments
     */
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

    /**
     * Method for leaving a tournament,
     * by deleting a row containing the user's id and tournament id from the Scores table
     *
     * @param User $user
     * @param $tournaments
     */
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