<?php

function list_matches($user_id) {
    $query = App\DB\query(
        "SELECT matches.id as match_id,matches.datetime, matches.home_team, matches.away_team,
            matches.home_goals as m_home_goals, matches.away_goals as m_away_goals, matches.tournament_id,
            predictions.home_goals as p_home_goals,predictions.away_goals as p_away_goals,
            predictions.points, predictions.score_added
            FROM matches
            INNER JOIN scores ON scores.tournament_id = matches.tournament_id AND scores.user_id = :user_id
            LEFT JOIN predictions ON predictions.match_id = matches.id AND predictions.user_id = :user_id
            ORDER BY matches.datetime",
        array('email' => $_SESSION['email'], 'user_id' => $user_id),
        $GLOBALS['db_conn']);

    if ($query) {
        $result = $query->fetchAll();
        date_default_timezone_set('EET');
        $time_now = time();

        // set disabled flag for matches which have started
        foreach ($result as &$row) {
            if ( $time_now >= strtotime($row['datetime']) ) {
                $row['disabled'] = "disabled";
            } else {
                $row['disabled'] = "";
            }
        }

        return $result;
    } else {
        return false;
    }
}

function validate_prediction($match_id, $home_goals, $away_goals) {
    // TO DO
    return true;
}

function make_prediction($user_id, $match_id, $home_goals, $away_goals) {
    $check_prediction = App\DB\query(
        "SELECT *
            FROM predictions
            WHERE match_id = :match_id AND user_id = :user_id",
        array('user_id' => $user_id, 'match_id' => $match_id),
        $GLOBALS['db_conn']);

    if ($check_prediction) {
        $query = App\DB\query(
            "UPDATE predictions
            SET home_goals = :home_goals , away_goals = :away_goals
            WHERE match_id = :match_id AND user_id = :user_id",
            array('user_id' => $user_id, 'match_id' => $match_id, 'home_goals' => $home_goals, 'away_goals' => $away_goals),
            $GLOBALS['db_conn']);
    } else {
        $query = App\DB\query(
            "INSERT IGNORE INTO predictions(match_id,user_id,home_goals,away_goals)
                VALUES(:match_id, :user_id, :home_goals, :away_goals)",
            array('user_id' => $user_id, 'match_id' => $match_id, 'home_goals' => $home_goals, 'away_goals' => $away_goals),
            $GLOBALS['db_conn']);
    }
}
