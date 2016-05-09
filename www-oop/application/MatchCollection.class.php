<?php

namespace Devlabs\App;

class MatchCollection
{
    private $notScored = array();
    private $alreadyScored = array();

    public function getNotScored(User $user, $tournament_id, $dateFrom, $dateTo)
    {
        $this->notScored = array();

        $sqlString1 = 'SELECT matches.id, matches.datetime, matches.home_team, matches.away_team,
                        matches.home_goals, matches.away_goals, matches.tournament_id
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

        if ($tournament_id === "ALL") {
            $sqlStatement = $sqlString1 . ' ' . $sqlString3;
        } else {
            $sqlStatement = $sqlString1 . ' ' . $sqlString2 . ' ' . $sqlString3;
            $sqlVariables['tournament_id'] = $tournament_id;
        }

        $query = $GLOBALS['db']->query($sqlStatement, $sqlVariables);

        if ($query) {
            foreach ($query as &$row) {
                $this->notScored[$row['id']] = new Match(
                    $row['id'],
                    $row['datetime'],
                    $row['home_team'],
                    $row['away_team'],
                    $row['home_goals'],
                    $row['away_goals'],
                    $row['tournament_id']
                );

                // set disabled flag for matches which have started
                $this->notScored[$row['id']]->setDisabled();
            }
        }

        return $this->notScored;
    }

    public function getAlreadyScored(User $user, $tournament_id, $dateFrom, $dateTo)
    {
        $this->alreadyScored = array();

        $sqlString1 = 'SELECT matches.id, matches.datetime, matches.home_team, matches.away_team,
                        matches.home_goals, matches.away_goals, matches.tournament_id
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

        if ($tournament_id === "ALL") {
            $sqlStatement = $sqlString1 . ' ' . $sqlString3;
        } else {
            $sqlStatement = $sqlString1 . ' ' . $sqlString2 . ' ' . $sqlString3;
            $sqlVariables['tournament_id'] = $tournament_id;
        }

        $query = $GLOBALS['db']->query($sqlStatement, $sqlVariables);

        if ($query) {
            foreach ($query as &$row) {
                $this->alreadyScored[$row['id']] = new Match(
                    $row['id'],
                    $row['datetime'],
                    $row['home_team'],
                    $row['away_team'],
                    $row['home_goals'],
                    $row['away_goals'],
                    $row['tournament_id']
                );
            }
        }

        return $this->alreadyScored;
    }
}