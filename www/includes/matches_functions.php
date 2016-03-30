<?php

function match_started($datetime) {
    date_default_timezone_set('EET');
    $time_ref = time();

    return ( $time_ref >= strtotime($datetime) );
}

function list_matches($user_id, $tournament_id) {
    $query = ( $tournament_id === "ALL" )
        ? App\DB\query(
            "SELECT matches.id as match_id, matches.datetime, matches.home_team, matches.away_team,
                matches.home_goals as m_home_goals, matches.away_goals as m_away_goals, matches.tournament_id,
                predictions.home_goals as p_home_goals,predictions.away_goals as p_away_goals,
                predictions.points, predictions.score_added
                FROM matches
                INNER JOIN scores ON scores.tournament_id = matches.tournament_id AND scores.user_id = :user_id
                LEFT JOIN predictions ON predictions.match_id = matches.id AND predictions.user_id = :user_id
                ORDER BY matches.tournament_id, matches.datetime",
                array('email' => $_SESSION['email'], 'user_id' => $user_id),
            $GLOBALS['db_conn'])
        : App\DB\query(
            "SELECT matches.id as match_id, matches.datetime, matches.home_team, matches.away_team,
                matches.home_goals as m_home_goals, matches.away_goals as m_away_goals, matches.tournament_id,
                predictions.home_goals as p_home_goals,predictions.away_goals as p_away_goals,
                predictions.points, predictions.score_added
                FROM matches
                INNER JOIN scores ON scores.tournament_id = matches.tournament_id AND scores.user_id = :user_id
                LEFT JOIN predictions ON predictions.match_id = matches.id AND predictions.user_id = :user_id
                WHERE matches.tournament_id = :tournament_id
                ORDER BY matches.tournament_id, matches.datetime",
            array('email' => $_SESSION['email'], 'user_id' => $user_id, 'tournament_id' => $tournament_id),
            $GLOBALS['db_conn']);

    if ($query) {
        $result = $query->fetchAll();

        // set disabled flag for matches which have started
        foreach ($result as &$row) {
            if ( match_started($row['datetime']) ) {
                $row['disabled'] = "disabled";
            } else {
                $row['disabled'] = "";
            }
        }

        return $result;
    } else {
        return array();
    }
}

function validate_prediction($match_id, $home_goals, $away_goals, &$prediction_status) {
    $is_data_invalid = ( $home_goals == "" || $away_goals == "" );

    if ( $is_data_invalid ) {
        $prediction_status = 'Please provide values for both home and away goals.';

        return false;
    } else if ( !(is_numeric($home_goals) && $home_goals >= 0) || !(is_numeric($away_goals) && $away_goals >= 0) ) {
        $prediction_status = 'Both home and away goals should be non-negative integers.';

        return false;
    } else if ( !check_match_time($match_id) ) {
        $prediction_status = 'Match has already started, cannot make or edit prediction.';

        return false;
    } else {
        $prediction_status = 'OK, valid bet.';
    }

    return true;
}

function check_match_time($match_id) {
    $query = App\DB\query(
        "SELECT datetime FROM matches WHERE id = :match_id",
        array('match_id' => $match_id),
        $GLOBALS['db_conn']);

    $match_datetime = $query->fetchAll()[0]['datetime'];

    if ( match_started($match_datetime) ) return false;

    return true;
}

function make_prediction($user_id, $match_id, $home_goals, $away_goals) {
    $home_goals = (int) $home_goals;
    $away_goals = (int) $away_goals;

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
