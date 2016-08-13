<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Entity\Tournament;
use Devlabs\SportifyBundle\Entity\Match;
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
            $apiObjectId = $teamData['team_id'];

            $apiMapping = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
                ->getByEntityTypeAndApiObjectId('Team', $footballApi, $apiObjectId);

            if (!$apiMapping) {
                // create new Team object by using the parsed data
                $team = new Team();
                $team->setName($teamData['name']);
                $team->setNameShort($teamData['name_short']);
                $team->addTournament($tournament);

                // prepare and execute queries
                $this->em->persist($team);
                $this->em->flush();

                // create API mapping for this object
                $this->createApiMapping($team, 'Team', $footballApi, $apiObjectId);
            }
        }
    }

    public function importFixtures(array $fixtures, Tournament $tournament, $footballApi)
    {
        $status = array();

        $status['fixtures_fetched'] = count($fixtures);
        $status['fixtures_added'] = 0;
        $status['fixtures_updated']= 0;

        foreach ($fixtures as $fixtureData) {
            $apiMatchId = $fixtureData['match_id'];

            $matchApiMapping = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
                ->getByEntityTypeAndApiObjectId('Match', $footballApi, $apiMatchId);

            if (!$matchApiMapping) {
                $apiHomeTeamId = $fixtureData['home_team_id'];
                $apiAwayTeamId = $fixtureData['away_team_id'];

                $homeTeamId = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
                    ->getByEntityTypeAndApiObjectId('Team', $footballApi, $apiHomeTeamId)
                    ->getEntityId();
                $awayTeamId = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
                    ->getByEntityTypeAndApiObjectId('Team', $footballApi, $apiAwayTeamId)
                    ->getEntityId();

                $homeTeam = $this->em->getRepository('DevlabsSportifyBundle:Team')
                    ->findOneById($homeTeamId);
                $awayTeam = $this->em->getRepository('DevlabsSportifyBundle:Team')
                    ->findOneById($awayTeamId);

                $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $fixtureData['match_local_time']);

                // create new Match object by using the parsed data
                $match = new Match();
                $match->setTournamentId($tournament);
                $match->setDatetime($datetime);
                $match->setHomeTeamId($homeTeam);
                $match->setAwayTeamId($awayTeam);

                // prepare and execute queries
                $this->em->persist($match);
                $this->em->flush();

                // create API mapping for this object
                $this->createApiMapping($match, 'Match', $footballApi, $apiMatchId);

                // increment the numeber of added fixtures
                $status['fixtures_added']++;

            } else if ($fixtureData['home_team_goals'] !== null && $fixtureData['away_team_goals'] !== null) {
                // get match from db
                $match = $this->em->getRepository('DevlabsSportifyBundle:Match')
                    ->findOneById($matchApiMapping->getEntityId());

                // update result (home and away goals)
                $match->setHomeGoals($fixtureData['home_team_goals']);
                $match->setAwayGoals($fixtureData['away_team_goals']);

                // prepare and execute queries
                $this->em->persist($match);
                $this->em->flush();

                // increment the numeber of added fixtures
                $status['fixtures_updated']++;
            }
        }

        return $status;
    }

    // create API mapping for this Team object
    private function createApiMapping($entityObject, $entityType, $apiName, $apiObjectId)
    {
        $apiMapping = new ApiMapping();
        $apiMapping->setEntityId($entityObject->getId());
        $apiMapping->setEntityType($entityType);
        $apiMapping->setApiName($apiName);
        $apiMapping->setApiObjectId($apiObjectId);

        // prepare and execute queries
        $this->em->persist($apiMapping);
        $this->em->flush();
    }
}