<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates\Parsers;

/**
 * Class FootballDataOrg
 * @package Devlabs\SportifyBundle\Services\DataUpdates\Parsers
 */
class FootballDataOrg
{
    /**
     * Method for parsing fetched Teams data
     *
     * @param array $teams
     * @return array
     */
    public function parseTeams(array $teams)
    {
        foreach ($teams as &$team) {
            $parsedTeam = array();

            $parsedTeam['api_team_id'] = $this->getNumberAtEndOfString($team->_links->self->href);
            $parsedTeam['name'] = $team->name;
            $parsedTeam['name_short'] = ($team->code) ? $team->code : 'TEAM'.$parsedTeam['api_team_id'];

            $team = $parsedTeam;
        }

        return $teams;
    }

    /**
     * Method for parsing fetched Fixtures data
     *
     * @param array $fixtures
     * @return array
     */
    public function parseFixtures(array $fixtures)
    {
        foreach ($fixtures as &$fixture) {
            $parsedFixture = array();

            $parsedFixture['api_match_id'] = $this->getNumberAtEndOfString($fixture->_links->self->href);
            $parsedFixture['api_tournament_id'] = $this->getNumberAtEndOfString($fixture->_links->competition->href);
            $parsedFixture['home_team_id'] = $this->getNumberAtEndOfString($fixture->_links->homeTeam->href);
            $parsedFixture['away_team_id'] = $this->getNumberAtEndOfString($fixture->_links->awayTeam->href);
            $parsedFixture['match_datetime'] = $fixture->date;

            $fixture = $parsedFixture;
        }

        return $fixtures;
    }

    /**
     * Method for extracting a number at end of a string
     *
     * @param $subject
     * @return mixed
     */
    private function getNumberAtEndOfString($string)
    {
        preg_match('/\d+$/', $string, $matches);

        return $matches[0];
    }
}