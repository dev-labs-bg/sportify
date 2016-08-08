<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates\Parsers;

/**
 * Class FootballDataOrg
 * @package Devlabs\SportifyBundle\Services\DataUpdates\Parsers
 */
class FootballDataOrg
{
    public function parseFixtures(array $fixtures)
    {
        foreach ($fixtures as &$fixture) {
            $fixtureData = array();

            $fixtureData['api_match_id'] = $this->getNumberAtEndOfString($fixture->_links->self->href);
            $fixtureData['api_tournament_id'] = $this->getNumberAtEndOfString($fixture->_links->competition->href);
            $fixtureData['home_team_id'] = $this->getNumberAtEndOfString($fixture->_links->homeTeam->href);
            $fixtureData['away_team_id'] = $this->getNumberAtEndOfString($fixture->_links->awayTeam->href);
            $fixtureData['match_datetime'] = $fixture->date;

            $fixture = $fixtureData;
        }

        return $fixtures;
    }

    private function getNumberAtEndOfString($subject)
    {
        preg_match('/\d+$/', $subject, $matches);
        return $matches[0];
    }
}