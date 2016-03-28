<?php

function list_tournaments_enrolled() {
    $query = App\DB\query(
            "SELECT tournaments.id, tournaments.name
                FROM scores
                INNER JOIN users ON users.id = scores.user_id
                INNER JOIN tournaments ON tournaments.id = scores.tournament_id
                WHERE users.email = :email",
            array('email' => $_SESSION['email']),
            $GLOBALS['db_conn']);

    if ($query) {
        return $query->fetchAll();
    } else {
        return false;
    }
}

function list_tournaments_not_enrolled() {
    $query = App\DB\query(
            "SELECT tournaments.id, tournaments.name
                FROM tournaments
                WHERE tournaments.id NOT IN
                    (SELECT scores.tournament_id
                    FROM scores
                    INNER JOIN users ON users.id = scores.user_id
                    WHERE users.email = :email)",
            array('email' => $_SESSION['email']),
            $GLOBALS['db_conn']);

    if ($query) {
        return $query->fetchAll();
    } else {
        return false;
    }
}

