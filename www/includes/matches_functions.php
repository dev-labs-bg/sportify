<?php

function list_matches($user_id) {
    $query = App\DB\query(
        "SELECT matches.*, predictions.*
            FROM matches
            INNER JOIN scores ON scores.tournament_id = matches.tournament_id AND scores.user_id = :user_id
            LEFT JOIN predictions ON predictions.match_id = matches.id AND predictions.user_id = :user_id",
        array('email' => $_SESSION['email'], 'user_id' => $user_id),
        $GLOBALS['db_conn']);

    if ($query) {
        return $query->fetchAll();
    } else {
        return false;
    }
}

//function list_tournaments_available() {
//    $query = App\DB\query(
//        "SELECT tournaments.id, tournaments.name
//                FROM tournaments
//                WHERE tournaments.id NOT IN
//                    (SELECT scores.tournament_id
//                    FROM scores
//                    INNER JOIN users ON users.id = scores.user_id
//                    WHERE users.email = :email)",
//        array('email' => $_SESSION['email']),
//        $GLOBALS['db_conn']);
//
//    if ($query) {
//        return $query->fetchAll();
//    } else {
//        return false;
//    }
//}
