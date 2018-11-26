<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates\Parsers;

/**
 * Class FootballDataOrg
 * @package Devlabs\SportifyBundle\Services\DataUpdates\Parsers
 */
class FootballDataOrg
{
    /**
     * Parse fetched Teams data
     *
     * @param array $teams
     * @return array
     */
    public function parseTeams(array $teams)
    {
        foreach ($teams as &$team) {
            $parsedTeam = array();

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
            $parsedTeam['team_id'] = $team->id;
=======
            $parsedTeam['team_id'] = $this->getNumberAtEndOfString($team->_links->self->href);
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
            $parsedTeam['team_id'] = $this->getNumberAtEndOfString($team->_links->self->href);
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
            $parsedTeam['team_id'] = $this->getNumberAtEndOfString($team->_links->self->href);
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
            $parsedTeam['team_id'] = $this->getNumberAtEndOfString($team->_links->self->href);
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
            $parsedTeam['name'] = $team->name;
            $parsedTeam['team_logo'] = $team->crestUrl;

            $team = $parsedTeam;
        }

        return $teams;
    }

    /**
     * Parse fetched Fixtures data
     *
     * @param array $fixtures
     * @return array
     */
    public function parseFixtures(array $fixtures)
    {
        foreach ($fixtures as &$fixture) {
            $parsedFixture = array();

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
            $parsedFixture['match_id'] = $fixture->id;
            $parsedFixture['tournament_id'] = $fixture->season->id;
            $parsedFixture['home_team_id'] = $fixture->homeTeam->id;
            $parsedFixture['away_team_id'] = $fixture->awayTeam->id;
            $parsedFixture['match_local_time'] = date('Y-m-d H:i:s', strtotime($fixture->utcDate));
            $parsedFixture['status'] = $fixture->status;

            if ($fixture->status === 'FINISHED') {
                $parsedFixture['home_team_goals'] = $fixture->score->fullTime->homeTeam;
                $parsedFixture['away_team_goals'] = $fixture->score->fullTime->awayTeam;
=======
=======
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
            $parsedFixture['match_id'] = $this->getNumberAtEndOfString($fixture->_links->self->href);
            $parsedFixture['tournament_id'] = $this->getNumberAtEndOfString($fixture->_links->competition->href);
            $parsedFixture['home_team_id'] = $this->getNumberAtEndOfString($fixture->_links->homeTeam->href);
            $parsedFixture['away_team_id'] = $this->getNumberAtEndOfString($fixture->_links->awayTeam->href);
            $parsedFixture['match_local_time'] = date('Y-m-d H:i:s', strtotime($fixture->date));
            $parsedFixture['status'] = $fixture->status;

            if ($fixture->status === 'FINISHED') {
                $parsedFixture['home_team_goals'] = $fixture->result->goalsHomeTeam;
                $parsedFixture['away_team_goals'] = $fixture->result->goalsAwayTeam;
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
            } else {
                $parsedFixture['home_team_goals'] = null;
                $parsedFixture['away_team_goals'] = null;
            }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
/*            if ($fixture->odds && ($fixture->odds !== 'null')) {
=======
            if ($fixture->odds && ($fixture->odds !== 'null')) {
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
            if ($fixture->odds && ($fixture->odds !== 'null')) {
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
            if ($fixture->odds && ($fixture->odds !== 'null')) {
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
            if ($fixture->odds && ($fixture->odds !== 'null')) {
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
                $parsedFixture['odds_home_win'] = $fixture->odds->homeWin;
                $parsedFixture['odds_draw'] = $fixture->odds->draw;
                $parsedFixture['odds_away_win'] = $fixture->odds->awayWin;
            } else {
                $parsedFixture['odds_home_win'] = null;
                $parsedFixture['odds_draw'] = null;
                $parsedFixture['odds_away_win'] = null;
            }
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
*/
=======

>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======

>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======

>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======

>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
            $fixture = $parsedFixture;
        }

        return $fixtures;
    }

    /**
     * Parse fetched tournaments data
     *
     * @param array $tournaments
     * @return array
     */
    public function parseTournaments(array $tournaments)
    {
        foreach ($tournaments as &$tournament) {
            $parsedTournament = array();

            $parsedTournament['id'] = $tournament->id;
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
            $parsedTournament['name'] = $tournament->name;
=======
            $parsedTournament['name'] = $tournament->caption;
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
            $parsedTournament['name'] = $tournament->caption;
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
            $parsedTournament['name'] = $tournament->caption;
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d
=======
            $parsedTournament['name'] = $tournament->caption;
>>>>>>> 56e806fedfe76ebc3a26647529c533bc25d8dd4d

            $tournament = $parsedTournament;
        }

        return $tournaments;
    }

    /**
     * Extract a number located at the end of a string
     *
     * @param $string
     * @return mixed
     */
    private function getNumberAtEndOfString($string)
    {
        preg_match('/\d+$/', $string, $matches);

        return $matches[0];
    }
}
