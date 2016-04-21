<?php

function list_tournaments_joined($tournament_id = "ALL") {
    $query = App\DB\query(
            "SELECT tournaments.id, tournaments.name, tournaments.start, tournaments.end
                FROM scores
                INNER JOIN users ON users.id = scores.user_id
                INNER JOIN tournaments ON tournaments.id = scores.tournament_id
                WHERE users.email = :email",
            array('email' => $_SESSION['email']),
            $GLOBALS['db_conn']);

    if ($query) {
        $result = $query->fetchAll();

        // set selected flag for selected tournament
        foreach ($result as &$row) {

            if ( $row['id'] == $tournament_id ) {
                $row['selected'] = "selected";
            } else {
                $row['selected'] = "";
            }
        }

        return $result;
    } else {
        return false;
    }
}

function list_tournaments_available($tournament_id = "ALL") {
    $query = App\DB\query(
            "SELECT tournaments.id, tournaments.name, tournaments.start, tournaments.end
                FROM tournaments
                WHERE tournaments.id NOT IN
                    (SELECT scores.tournament_id
                    FROM scores
                    INNER JOIN users ON users.id = scores.user_id
                    WHERE users.email = :email)",
            array('email' => $_SESSION['email']),
            $GLOBALS['db_conn']);

    if ($query) {
        $result = $query->fetchAll();

        // set selected flag for selected tournament
        foreach ($result as &$row) {

            if ( $row['id'] == $tournament_id ) {
                $row['selected'] = "selected";
            } else {
                $row['selected'] = "";
            }
        }

        return $result;
    } else {
        return false;
    }
}

function join_tournaments($user_id, $tournaments) {
    foreach ($tournaments as $tournament_id) {
        $query =  App\DB\query(
            "INSERT IGNORE INTO scores(user_id,tournament_id,points)
                VALUES(:user_id, :tournament_id, 0)",
            array('user_id' => $user_id, 'tournament_id' => $tournament_id),
            $GLOBALS['db_conn']);
    }
}

function leave_tournaments($user_id, $tournaments) {
    foreach ($tournaments as $tournament_id) {
        $query =  App\DB\query(
            "DELETE FROM scores WHERE user_id = :user_id AND tournament_id = :tournament_id",
            array('user_id' => $user_id, 'tournament_id' => $tournament_id),
            $GLOBALS['db_conn']);
    }
}