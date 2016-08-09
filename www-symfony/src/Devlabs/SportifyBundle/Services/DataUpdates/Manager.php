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
     * Method for updating fixtures via API Fetch, Parse and Import services
     */
    public function updateFixtures()
    {
        // get all tournaments
        $tournaments = $this->em->getRepository('DevlabsSportifyBundle:Tournament')->findAll();

        if (!$tournaments) {
           return;
        }

        // set dateFrom and dateTo to respectively today's date and 1 week on
        $dateFrom = date("Y-m-d");
        $dateTo = date("Y-m-d", time() + 604800);

        // iterate the following actions for each tournament
        foreach ($tournaments as $tournament) {
            if ($tournament->getChampionTeamId() !== null) continue;

            $apiMapping = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
                ->getByEntityAndApiProvider($tournament, 'Tournament', $this->footballApi);
            $apiTournamentId = $apiMapping->getApiObjectId();

            $fetchedFixtures = $this->dataFetcher->fetchFixturesByTournamentAndTimeRange($apiTournamentId, $dateFrom, $dateTo);

            $parsedFixtures = $this->dataParser->parseFixtures($fetchedFixtures);
            var_dump($parsedFixtures);

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