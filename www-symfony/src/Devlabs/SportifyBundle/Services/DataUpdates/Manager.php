<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class Manager
 * @package Devlabs\SportifyBundle\Services\DataUpdates
 */
class Manager
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Method for setting EntityManager
     *
     * @param ObjectManager $em
     * @return $this
     */
    public function setEntityManager(ObjectManager $em)
    {
        $this->em = $em;

        return $this;
    }

    public function updateFixtures()
    {
        // get all tournaments
        $tournaments = $this->em->getRepository('DevlabsSportifyBundle:Tournament')->findAll();

        if (!$tournaments) {
           return;
        }

        $footballApi = 'football_data_org';
        $fetcherService = 'app.data_updates.fetchers.'.$footballApi;
        $parserService = 'app.data_updates.parsers.'.$footballApi;

        $dataFetcher = $this->container->get($fetcherService);
        $dataFetcher->setApiToken('896fa7a2adc1473ba474c6eb4e66cb4c');

        // set dateFrom and dateTo to respectively today's date and 2 weeks on
        $dateFrom = date("Y-m-d");
        $dateTo = date("Y-m-d", time() + 604800);

        // iterate the following actions for each tournament
        foreach ($tournaments as $tournament) {
            if ($tournament->getChampionTeamId() !== null) continue;

            $apiTournamentId = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
                ->getByEntityAndApiProvider($tournament, 'Tournament', $footballApi)
                ->getApiObjectId();
            $dataFetched = $dataFetcher->fetchFixturesByTournamentAndTimeRange($apiTournamentId, $dateFrom, $dateTo);

            var_dump($dataFetched[0]->_links->homeTeam);
            $dataParsed = $parserService->parseFixtures($dataFetched);

            // invoke the parser service
            // parse the fetched data

            // invoke the importer service
            // import the parsed data
        }
    }

    public function updateMatchScores()
    {
    }
}