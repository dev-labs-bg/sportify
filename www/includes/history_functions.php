<?php

function list_scored_matches($user_id, $tournament_id, $date_from, $date_to) {

    $query = ( $tournament_id === "ALL" )
        ? App\DB\query(
            "SELECT matches.id as match_id, matches.datetime, matches.home_team, matches.away_team,
                matches.home_goals as m_home_goals, matches.away_goals as m_away_goals, matches.tournament_id,
                predictions.home_goals as p_home_goals,predictions.away_goals as p_away_goals,
                predictions.points, predictions.score_added
                FROM matches
                INNER JOIN scores ON scores.tournament_id = matches.tournament_id AND scores.user_id = :user_id
                LEFT JOIN predictions ON predictions.match_id = matches.id AND predictions.user_id = :user_id
                WHERE (predictions.score_added = 1)
                    AND (matches.datetime >= :date_from AND matches.datetime <= :date_to)
                ORDER BY matches.tournament_id, matches.datetime",
            array('email' => $_SESSION['email'], 'user_id' => $user_id, 'date_from' => $date_from, 'date_to' => $date_to),
            $GLOBALS['db_conn'])
        : App\DB\query(
            "SELECT matches.id as match_id, matches.datetime, matches.home_team, matches.away_team,
                matches.home_goals as m_home_goals, matches.away_goals as m_away_goals, matches.tournament_id,
                predictions.home_goals as p_home_goals,predictions.away_goals as p_away_goals,
                predictions.points, predictions.score_added
                FROM matches
                INNER JOIN scores ON scores.tournament_id = matches.tournament_id AND scores.user_id = :user_id
                LEFT JOIN predictions ON predictions.match_id = matches.id AND predictions.user_id = :user_id
                WHERE (predictions.score_added = 1)
                    AND (matches.datetime >= :date_from AND matches.datetime <= :date_to)
                    AND matches.tournament_id = :tournament_id
                ORDER BY matches.tournament_id, matches.datetime",
            array('email' => $_SESSION['email'], 'user_id' => $user_id, 'tournament_id' => $tournament_id, 'date_from' => $date_from, 'date_to' => $date_to),
            $GLOBALS['db_conn']);

    if ($query)
        return $query->fetchAll();

    return array();

}
