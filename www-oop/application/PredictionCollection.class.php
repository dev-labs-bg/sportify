<?php

namespace Devlabs\App;

/**
 * Class PredictionCollection
 * @package Devlabs\App
 */
class PredictionCollection
{
    private $notScored = array();
    private $alreadyScored = array();

    /**
     * Method for getting a list of the predictions for matches which have not been scored/finished yet
     *
     * @param User $user
     * @param $tournament_id
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getNotScored(User $user, $tournament_id, $dateFrom, $dateTo)
    {
        $this->notScored = array();

        $sqlString1 = 'SELECT predictions.id, matches.id as match_id, predictions.user_id,
                        predictions.home_goals, predictions.away_goals, predictions.points, predictions.score_added
                        FROM matches
                        INNER JOIN scores ON scores.tournament_id = matches.tournament_id AND scores.user_id = :user_id
                        LEFT JOIN predictions ON predictions.match_id = matches.id AND predictions.user_id = :user_id
                        WHERE (predictions.score_added IS NULL OR predictions.score_added = 0)
                            AND (matches.home_goals IS NULL OR matches.away_goals IS NULL)
                            AND (matches.datetime >= :date_from AND matches.datetime <= :date_to)';
        $sqlString2 = ' AND matches.tournament_id = :tournament_id ';
        $sqlString3 = 'ORDER BY matches.tournament_id, matches.datetime, matches.home_team';

        $sqlVariables = array(
            'user_id' => $user->id,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        );

        // prepare a different SQL statement, if we want to filter for a particular tournament
        if ($tournament_id === "ALL") {
            $sqlStatement = $sqlString1 . ' ' . $sqlString3;
        } else {
            $sqlStatement = $sqlString1 . ' ' . $sqlString2 . ' ' . $sqlString3;
            $sqlVariables['tournament_id'] = $tournament_id;
        }

        $query = $GLOBALS['db']->query($sqlStatement, $sqlVariables);

        if ($query) {
            foreach ($query as &$row) {
                $this->notScored[$row['match_id']] = new Prediction(
                    $row['id'],
                    $row['match_id'],
                    $row['user_id'],
                    $row['home_goals'],
                    $row['away_goals'],
                    $row['points'],
                    $row['score_added']
                );
            }
        }

        return $this->notScored;
    }

    /**
     * Method for getting a list of the predictions for matches which have already been scored/finished
     *
     * @param User $user
     * @param $tournament_id
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getAlreadyScored(User $user, $tournament_id, $dateFrom, $dateTo)
    {
        $this->alreadyScored = array();

        $sqlString1 = 'SELECT predictions.id, matches.id as match_id, predictions.user_id,
                        predictions.home_goals, predictions.away_goals, predictions.points, predictions.score_added
                        FROM matches
                        INNER JOIN scores ON scores.tournament_id = matches.tournament_id AND scores.user_id = :user_id
                        LEFT JOIN predictions ON predictions.match_id = matches.id AND predictions.user_id = :user_id
                        WHERE (predictions.score_added = 1)
                            AND (matches.datetime >= :date_from AND matches.datetime <= :date_to)';
        $sqlString2 = ' AND matches.tournament_id = :tournament_id ';
        $sqlString3 = 'ORDER BY matches.tournament_id, matches.datetime, matches.home_team';

        $sqlVariables = array(
            'user_id' => $user->id,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        );

        // prepare a different SQL statement, if we want to filter for a particular tournament
        if ($tournament_id === "ALL") {
            $sqlStatement = $sqlString1 . ' ' . $sqlString3;
        } else {
            $sqlStatement = $sqlString1 . ' ' . $sqlString2 . ' ' . $sqlString3;
            $sqlVariables['tournament_id'] = $tournament_id;
        }

        $query = $GLOBALS['db']->query($sqlStatement, $sqlVariables);

        if ($query) {
            foreach ($query as &$row) {
                $this->notScored[$row['match_id']] = new Prediction(
                    $row['id'],
                    $row['match_id'],
                    $row['user_id'],
                    $row['home_goals'],
                    $row['away_goals'],
                    $row['points'],
                    $row['score_added']
                );
            }
        }

        return $this->alreadyScored;
    }
}