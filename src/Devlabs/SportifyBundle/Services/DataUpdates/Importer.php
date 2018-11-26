<?php

namespace Devlabs\SportifyBundle\Services\DataUpdates;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
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

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }

    /**
     * Import teams for a tournament by using parsed data
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
                $team->addTournament($tournament);

                // prepare and execute queries
                $this->em->persist($team);
                $this->em->flush();

                // create API mapping for this object
                $apiMapping = $this->createApiMapping($team, 'Team', $footballApi, $apiObjectId);

                // prepare queries
                $this->em->persist($apiMapping);
            } else {
                // get Team from DB
                $team = $this->em->getRepository('DevlabsSportifyBundle:Team')
                    ->find($apiMapping->getEntityId());

                // add Tournament to Team's tournaments list if NOT already present
                if (!$team->getTournaments()->contains($tournament)) {
                    $team->addTournament($tournament);

                    // prepare queries
                    $this->em->persist($team);
                }
            }

            // execute queries
            $this->em->flush();

            // set Team logo if none set
            if (!$team->hasTeamLogo())
                $team->setTeamLogo($teamData['team_logo']);
        }
    }

    /**
     * Import fixtures for a tournament by using parsed data
     *
     * @param array $fixtures
     * @param Tournament $tournament
     * @param $footballApi
     * @return array
     */
    public function importFixtures(array $fixtures, Tournament $tournament, $footballApi)
    {
        $status = array();

        $status['fixtures_fetched'] = count($fixtures);
        $status['fixtures_added'] = 0;
        $status['fixtures_updated'] = 0;

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
                $apiMapping = $this->createApiMapping($match, 'Match', $footballApi, $apiMatchId);
                $this->em->persist($apiMapping);

                // increment the number of added fixtures
                $status['fixtures_added']++;

            } else {
                // get match from db
                $match = $this->em->getRepository('DevlabsSportifyBundle:Match')
                    ->findOneById($matchApiMapping->getEntityId());

                $matchUpdated = false;

                $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $fixtureData['match_local_time']);
                if ($match->getDatetime() !== $datetime) {
                    $match->setDatetime($datetime);
//                    $matchUpdated = true;
                }

                // updating Match's home and away goals if they are not already set
                if (($fixtureData['home_team_goals'] !== null) && ($fixtureData['away_team_goals'] !== null) &&
                    ($match->getHomeGoals() === null) && ($match->getAwayGoals() === null)) {
                    $match->setHomeGoals($fixtureData['home_team_goals']);
                    $match->setAwayGoals($fixtureData['away_team_goals']);
                    $matchUpdated = true;
                }

                if ($matchUpdated === true) {
                    // increment the number of updated fixtures
                    $status['fixtures_updated']++;
                }

                // prepare db queries
                $this->em->persist($match);
            }

            // execute queries
            $this->em->flush();
        }

        return $status;
    }

    /**
     * Create API mapping for an entity object
     *
     * @param $entityObject
     * @param $entityType
     * @param $apiName
     * @param $apiObjectId
     * @return ApiMapping
     */
    private function createApiMapping($entityObject, $entityType, $apiName, $apiObjectId)
    {
        $apiMapping = new ApiMapping();
        $apiMapping->setEntityId($entityObject->getId());
        $apiMapping->setEntityType($entityType);
        $apiMapping->setApiName($apiName);
        $apiMapping->setApiObjectId($apiObjectId);

        return $apiMapping;
    }
}
