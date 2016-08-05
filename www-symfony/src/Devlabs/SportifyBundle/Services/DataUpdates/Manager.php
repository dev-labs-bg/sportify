<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Manager
 * @package Devlabs\SportifyBundle\Services\DataUpdates
 */
class Manager
{
    use ContainerAwareTrait;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function updateFixtures()
    {
        $dataFetcher = $this->container->get('app.data_updates.fetchers.football_data');
        $dataFetcher->setApiToken('896fa7a2adc1473ba474c6eb4e66cb4c');

        // get all tournaments from DB

        // iterate the following actions for each tournament:

        $dataFetched = $dataFetcher->fetchFixturesByTournamentAndDateRange($tournamentId, $dateFrom, $dateTo);

        // invoke the parser service
        // parse the fetched data

        // invoke the importer service
        // import the parsed data
    }

    public function updateMatchScores()
    {
    }
}