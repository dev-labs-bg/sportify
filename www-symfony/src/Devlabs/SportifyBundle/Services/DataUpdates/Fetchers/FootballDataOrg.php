<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates\Fetchers;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

/**
 * Class FootballDataOrg
 * @package Devlabs\SportifyBundle\Services\DataUpdates\Fetchers
 */
class FootballDataOrg
{
    use ContainerAwareTrait;

    private $httpClient;
    private $options;
    private $baseUri;

    public function __construct(ContainerInterface $container, $baseUri, $apiToken)
    {
        $this->container = $container;
        $this->httpClient = new Client();
        $this->options = array();
        $this->options['headers']['X-Auth-Token'] = $apiToken;
        $this->baseUri = $baseUri;
    }

    /**
     * Get response for GET request to given URL
     *
     * @param $uri
     * @return mixed
     */
    public function getResponse($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return new Response(400);
        }

        try {
            $response = $this->httpClient->get($url, $this->options);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    /**
     * Process fetched response depending on status code
     *
     * @param Response $response
     * @param null $bodyProperty
     * @return array|mixed
     */
    public function processResponse(Response $response, $bodyProperty = null)
    {
        if ($response->getStatusCode() !== 200) {
            $this->container->get('session')
                ->getFlashBag()
                ->add('message', $response->getReasonPhrase());

            return array();
        }

        return ($bodyProperty)
            ? json_decode($response->getBody())->$bodyProperty
            : json_decode($response->getBody());
    }

    /**
     * Fetch fixtures by API tournament ID and MatchDay
     *
     * @param $apiTournamentId
     * @param $matchDay
     * @return mixed
     */
    public function fetchFixturesByTournamentAndMatchDay($apiTournamentId, $matchDay)
    {
        $uri = $this->baseUri.'/competitions/'.$apiTournamentId.'/fixtures/?matchday='.$matchDay;

        return $this->processResponse($this->getResponse($uri), 'fixtures');
    }

    /**
     * Fetch fixtures by API tournament ID and date/time range
     *
     * @param $apiTournamentId
     * @param $dateFrom
     * @param $dateTo
     * @return mixed
     */
    public function fetchFixturesByTournamentAndTimeRange($apiTournamentId, $dateFrom, $dateTo)
    {
        $uri = $this->baseUri.'/competitions/'.$apiTournamentId.'/fixtures/?timeFrameStart='.$dateFrom.'&timeFrameEnd='.$dateTo;

        return $this->processResponse($this->getResponse($uri), 'fixtures');
    }

    /**
     * Fetch teams by API tournament ID
     *
     * @param $apiTournamentId
     * @return mixed
     */
    public function fetchTeamsByTournament($apiTournamentId)
    {
        $uri = $this->baseUri.'/competitions/'.$apiTournamentId.'/teams';

        return $this->processResponse($this->getResponse($uri), 'teams');
    }

    /**
     * Fetch all tournaments/competitions from the API
     *
     * @return mixed
     */
    public function fetchAllTournaments()
    {
        $uri = $this->baseUri.'/competitions';

        return $this->processResponse($this->getResponse($uri));
    }
}