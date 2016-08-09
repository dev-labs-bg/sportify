<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Entity\Tournament;
use Devlabs\SportifyBundle\Entity\Team;
use Devlabs\SportifyBundle\Entity\ApiMapping;

/**
 * Class Importer
 * @package Devlabs\SportifyBundle\Services\DataUpdates
 */
class Importer
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

    /**
     * Method for importing teams for a tournament
     * by using parsed data
     *
     * @param array $teams
     * @param Tournament $tournament
     * @param $footballApi
     */
    public function importTeams(array $teams, Tournament $tournament, $footballApi)
    {
        foreach ($teams as $teamData) {
            $apiObjectId = $teamData['api_team_id'];

            $apiMapping = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
                ->getByEntityTypeAndApiObjectId('Team', $footballApi, $apiObjectId);

            if (!$apiMapping) {
                $team = new Team();
                $team->setName($teamData['name']);
                $team->setNameShort($teamData['name_short']);
                $team->addTournament($tournament);

                // prepare and execute queries
                $this->em->persist($team);
                $this->em->flush();

                // create API mapping for this Team object
                $apiMapping = new ApiMapping();
                $apiMapping->setEntityId($team->getId());
                $apiMapping->setEntityType('Team');
                $apiMapping->setApiName($footballApi);
                $apiMapping->setApiObjectId($apiObjectId);

                // prepare and execute queries
                $this->em->persist($apiMapping);
                $this->em->flush();
            }
        }
    }
}