<?php

function get_not_scored_predictions() {

    $query = App\DB\query(
            "SELECT matches.id as match_id, matches.home_goals as m_home_goals, matches.away_goals as m_away_goals,
                    matches.tournament_id, predictions.id as p_id, predictions.user_id as user_id,
                    predictions.home_goals as p_home_goals,predictions.away_goals as p_away_goals,
                    predictions.points, predictions.score_added
                FROM matches
                INNER JOIN predictions ON predictions.match_id = matches.id
                WHERE (matches.home_goals IS NOT NULL AND matches.away_goals IS NOT NULL)
                      AND (predictions.score_added IS NULL OR predictions.score_added = 0)
                      AND predictions.id IS NOT NULL
                ORDER BY matches.id",
            array(),
            $GLOBALS['db_conn']);

    if ($query)
        return $query->fetchAll();

    return array();

}

function update_predictions_points($data) {
    foreach ($data as $row) {

        if ( ($row['m_home_goals'] == $row['p_home_goals']) && ($row['m_away_goals'] == $row['p_away_goals']) ) {
            $prediction_points = POINTS_EXACT;
        } else if ( ($row['m_home_goals'] > $row['m_away_goals'] && $row['p_home_goals'] > $row['p_away_goals'])
                    || ($row['m_home_goals'] < $row['m_away_goals'] && $row['p_home_goals'] < $row['p_away_goals'])
                    || ($row['m_home_goals'] == $row['m_away_goals'] && $row['p_home_goals'] == $row['p_away_goals']) ) {
            $prediction_points = POINTS_OUTCOME;
        } else {
            $prediction_points = 0;
        }

        $query = App\DB\query(
            "UPDATE predictions
                SET points = :p_points , score_added = 1
                WHERE id = :p_id",
            array('p_id' => $row['p_id'], 'p_points' => $prediction_points),
            $GLOBALS['db_conn']);

        if ( $prediction_points > 0 ) {
            $query = App\DB\query(
                "UPDATE scores
                SET points = points + :p_points
                WHERE user_id = :user_id AND tournament_id = :tournament_id",
                array('user_id' => $row['user_id'], 'tournament_id' => $row['tournament_id'], 'p_points' => $prediction_points),
                $GLOBALS['db_conn']);
        }

    }
}
