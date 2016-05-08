<?php

namespace Devlabs\App;

class TournamentsCollection
{
    public $items = array();

    public function getJoined(User $user, $tournament_id = "ALL")
    {
        $this->items = array();

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

                $this->items[] = new Tournament($row['id'], $row['name'], $row['start'], $row['end'], $row['selected']);
            }

            return $this->items;
        }

        return $this->items;
    }

    public function getAvailable(User $user, $tournament_id = "ALL")
    {
        $this->items = array();

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

                $this->items[] = new Tournament($row['id'], $row['name'], $row['start'], $row['end'], $row['selected']);
            }

            return $this->items;
        }

        return $this->items;
    }
}