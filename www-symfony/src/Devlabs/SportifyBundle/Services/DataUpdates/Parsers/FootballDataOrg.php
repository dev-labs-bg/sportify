<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates\Parsers;

/**
 * Class FootballDataOrg
 * @package Devlabs\SportifyBundle\Services\DataUpdates\Parsers
 */
class FootballDataOrg
{
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

    private function getNumberAtEndOfString($subject)
    {
        preg_match('/\d+$/', $subject, $matches);
        return $matches[0];
    }
}