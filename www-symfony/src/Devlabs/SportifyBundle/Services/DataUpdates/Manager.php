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
        $footballApiName = 'football_data_org';
        $fetcherServiceName = 'app.data_updates.fetchers.'.$footballApiName;
        $dataFetcher = $this->container->get($fetcherServiceName);
        $dataFetcher->setApiToken('896fa7a2adc1473ba474c6eb4e66cb4c');

        // get all tournaments
        $tournaments = $this->em->getRepository('DevlabsSportifyBundle:Tournament')->findAll();

        // set dateFrom and dateTo to respectively today's date and 2 weeks on
        $dateFrom = date("Y-m-d");
        $dateTo = date("Y-m-d", time() + 1209600);

        // iterate the following actions for each tournament
        foreach ($tournaments as $tournament) {
            // $apiTournamentId = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
            //              ->getByEntityAndApiProvider('Tournament', $tournament->getId(), $footballApi);
            $dataFetched = $dataFetcher->fetchFixturesByTournamentAndDateRange($apiTournamentId, $dateFrom, $dateTo);

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