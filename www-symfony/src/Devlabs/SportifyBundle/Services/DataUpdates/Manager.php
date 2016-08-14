<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Entity\Tournament;

/**
 * Class Manager
 * @package Devlabs\SportifyBundle\Services\DataUpdates
 */
class Manager
{
    use ContainerAwareTrait;

    private $em;
    private $footballApi;
    private $dataFetcher;
    private $dataParser;
    private $dataImporter;

    public function __construct(ContainerInterface $container, $footballApi = 'football_data_org')
    {
        $this->container = $container;
        $this->footballApi = $footballApi;

        $this->dataFetcher = $this->container->get('app.data_updates.fetchers.'.$footballApi);
        $this->dataFetcher->setApiToken('896fa7a2adc1473ba474c6eb4e66cb4c');

        $this->dataParser = $this->container->get('app.data_updates.parsers.'.$footballApi);

        $this->dataImporter = $this->container->get('app.data_updates.importer');
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

    /**
     * Method for updating teams via API Fetch, Parse and Import services
     *
     * @param Tournament $tournament
     */
    public function updateTeamsByTournament(Tournament $tournament)
    {
        $apiMapping = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
            ->getByEntityAndApiProvider($tournament, 'Tournament', $this->footballApi);
        $apiTournamentId = $apiMapping->getApiObjectId();

        $fetchedTeams = $this->dataFetcher->fetchTeamsByTournament($apiTournamentId);

        // parse the fetched data
        $parsedTeams = $this->dataParser->parseTeams($fetchedTeams);

        // invoke Importer service and import parsed data
        $this->dataImporter->setEntityManager($this->em);
        $this->dataImporter->importTeams($parsedTeams, $tournament, $this->footballApi);
    }

    /**
     * Method for updating fixtures data via API Fetch, Parse and Import services
     * for a given time range (start date and end date)
     */
    public function updateFixtures($dateFrom, $dateTo)
    {
        // get all tournaments
        $tournaments = $this->em->getRepository('DevlabsSportifyBundle:Tournament')->findAll();

        // return if no tournaments in db
        if (!$tournaments) {
           return;
        }

        $status = array();

        // iterate the following actions for each tournament
        foreach ($tournaments as $tournament) {
            $apiMapping = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
                ->getByEntityAndApiProvider($tournament, 'Tournament', $this->footballApi);

            // skip tournament if finished or there is no API mapping for it
            if (($tournament->getChampionTeamId() !== null) || (!$apiMapping)) continue;

            $status[$tournament->getId()]['name'] = $tournament->getName();

            // get the API tournament ID
            $apiTournamentId = $apiMapping->getApiObjectId();

            // fetch fixture data from API for given time range
            $fetchedFixtures = $this->dataFetcher->fetchFixturesByTournamentAndTimeRange($apiTournamentId, $dateFrom, $dateTo);

            // parse the fetched fixture data from API
            $parsedFixtures = $this->dataParser->parseFixtures($fetchedFixtures);

            // invoke Importer service and import parsed data
            $this->dataImporter->setEntityManager($this->em);

            $status[$tournament->getId()]['status'] = $this->dataImporter->importFixtures($parsedFixtures, $tournament, $this->footballApi);
        }

        return $status;
    }
}