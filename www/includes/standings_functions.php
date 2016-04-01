<?php

function list_standings($tournament_id) {

    $query = App\DB\query(
            "SELECT users.email, scores.tournament_id, scores.points
                FROM scores
                INNER JOIN users ON users.id = scores.user_id
                WHERE scores.tournament_id = :tournament_id
                ORDER BY scores.points DESC",
            array('tournament_id' => $tournament_id),
            $GLOBALS['db_conn']);

    if ($query)
        return $query->fetchAll();

    return array();
}

function list_all_tournaments() {
    $query = App\DB\query(
            "SELECT tournaments.id, tournaments.name
                FROM tournaments",
        array(),
        $GLOBALS['db_conn']);

    if ($query)
        return $query->fetchAll();

    return false;

}

function get_tournament_name($tournament_id) {
    $query = App\DB\query(
            "SELECT name
                FROM tournaments
                WHERE id = :tournament_id",
        array('tournament_id' => $tournament_id),
        $GLOBALS['db_conn']);

    if ($query)
        return $query->fetchAll()[0]['name'];

    return false;

}