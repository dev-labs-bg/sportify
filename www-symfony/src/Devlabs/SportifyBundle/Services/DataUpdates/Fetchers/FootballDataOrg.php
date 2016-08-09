<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates\Fetchers;

use GuzzleHttp\Client;

/**
 * Class FootballDataOrg
 * @package Devlabs\SportifyBundle\Services\DataUpdates\Fetchers
 */
class FootballDataOrg
{
    private $httpClient;
    private $options;
    private $baseUri;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->options = array();
        $this->baseUri = 'http://api.football-data.org/v1';
    }

    /**
     * Set the API token in the header
     *
     * @param $apiToken
     * @return $this
     */
    public function setApiToken($apiToken)
    {
        $this->options['headers']['X-Auth-Token'] = $apiToken;

        return $this;
    }

    /**
     * Get response for GET request to given URI and JSON-decode it
     *
     * @param $uri
     * @return mixed
     */
    public function getResponse($uri)
    {
        $response = $this->httpClient->get($uri, $this->options);

        return json_decode($response->getBody());
    }

    /**
     * Method for fetching fixtures by API tournament ID and MatchDay
     *
     * @param $apiTournamentId
     * @param $matchDay
     * @return mixed
     */
    public function fetchFixturesByTournamentAndMatchDay($apiTournamentId, $matchDay)
    {
        $uri = $this->baseUri.'/competitions/'.$apiTournamentId.'/fixtures/?matchday='.$matchDay;

        return $this->getResponse($uri)->fixtures;
    }

    /**
     * Method for fetching fixtures by API tournament ID and date/time range
     *
     * @param $apiTournamentId
     * @param $dateFrom
     * @param $dateTo
     * @return mixed
     */
    public function fetchFixturesByTournamentAndTimeRange($apiTournamentId, $dateFrom, $dateTo)
    {
        $uri = $this->baseUri.'/competitions/'.$apiTournamentId.'/fixtures/?timeFrameStart='.$dateFrom.'&timeFrameEnd='.$dateTo;

        return $this->getResponse($uri)->fixtures;
    }

    /**
     * Method for fetching teams by API tournament ID
     *
     * @param $apiTournamentId
     * @return mixed
     */
    public function fetchTeamsByTournament($apiTournamentId)
    {
        $uri = $this->baseUri.'/competitions/'.$apiTournamentId.'/teams';

        return $this->getResponse($uri)->teams;
    }
}