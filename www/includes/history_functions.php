<?php

function list_scored_matches($user_id, $tournament_id, $date_from, $date_to) {
    $string_1 = 'SELECT matches.id as match_id, matches.datetime, matches.home_team, matches.away_team,
                matches.home_goals as m_home_goals, matches.away_goals as m_away_goals, matches.tournament_id,
                predictions.home_goals as p_home_goals,predictions.away_goals as p_away_goals,
                predictions.points, predictions.score_added
                FROM matches
                INNER JOIN scores ON scores.tournament_id = matches.tournament_id AND scores.user_id = :user_id
                LEFT JOIN predictions ON predictions.match_id = matches.id AND predictions.user_id = :user_id
                WHERE (predictions.score_added = 1)
                    AND (matches.datetime >= :date_from AND matches.datetime <= :date_to)';
    $string_2 = 'AND matches.tournament_id = :tournament_id';
    $string_3 = 'ORDER BY matches.tournament_id, matches.datetime, matches.home_team';

    $sql_vars = array('email' => $_SESSION['email'], 'user_id' => $user_id, 'date_from' => $date_from, 'date_to' => $date_to);

    if ( $tournament_id === "ALL" ) {
        $sql_stmt = $string_1 . ' ' . $string_3;
    } else {
        $sql_stmt = $string_1 . ' ' . $string_2 . ' ' . $string_3;
        $sql_vars['tournament_id'] = $tournament_id;
    }

    $query = App\DB\query($sql_stmt, $sql_vars, $GLOBALS['db_conn']);

    if ($query)
        return $query->fetchAll();

    return array();

}
