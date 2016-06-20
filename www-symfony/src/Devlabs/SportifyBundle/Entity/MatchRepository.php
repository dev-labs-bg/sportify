<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class MatchRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class MatchRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Method for getting a list of the matches which have not been scored/finished yet
     *
     * @param User $user
     * @param $tournament_id
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getNotScored(User $user, $tournamentId, $dateFrom, $dateTo)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('m')
            ->from('DevlabsSportifyBundle:Match', 'm')
            ->join('m.tournamentId', 't')
            ->join('t.scores', 's', 'WITH', 's.userId = :user_id')
            ->leftJoin('m.predictions', 'p', 'WITH', 'p.userId = :user_id')
            ->where('p.scoreAdded IS NULL OR p.scoreAdded = 0')
            ->andWhere('m.homeGoals IS NULL OR m.awayGoals IS NULL')
            ->andWhere('m.datetime >= :date_from AND m.datetime <= :date_to')
            ->orderBy('m.tournamentId')
            ->addOrderBy('m.datetime')
            ->addOrderBy('m.homeTeam')
            ->setParameters(array(
                'user_id' => $user->getId(),
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ));

        // prepare a different query, if a tournament is selected for filtering
        if ($tournamentId !== 'all') {
            $query->andWhere('m.tournamentId = :tournament_id')
                ->setParameter('tournament_id', $tournamentId);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Method for getting a list of the matches which have already been scored/finished
     *
     * @param User $user
     * @param $tournamentId
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getAlreadyScored(User $user, $tournamentId, $dateFrom, $dateTo)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('m')
            ->from('DevlabsSportifyBundle:Match', 'm')
            ->join('m.tournamentId', 't')
            ->join('t.scores', 's', 'WITH', 's.userId = :user_id')
            ->leftJoin('m.predictions', 'p', 'WITH', 'p.userId = :user_id')
            ->where('p.scoreAdded = 1 OR p.id IS NULL')
            ->andWhere('m.homeGoals IS NOT NULL AND m.awayGoals IS NOT NULL')
            ->andWhere('m.datetime >= :date_from AND m.datetime <= :date_to')
            ->orderBy('m.tournamentId')
            ->addOrderBy('m.datetime', 'DESC')
            ->addOrderBy('m.homeTeam')
            ->setParameters(array(
                'user_id' => $user->getId(),
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ));

        // prepare a different query, if a tournament is selected for filtering
        if ($tournamentId !== 'all') {
            $query->andWhere('m.tournamentId = :tournament_id')
                ->setParameter('tournament_id', $tournamentId);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Method for getting a list of the matches which have final score
     * but there are NOT SCORED predictions for these matches
     *
     * @return array
     */
    public function getFinishedNotScored()
    {
        $queryResult = $this->getEntityManager()->createQueryBuilder()
            ->select('DISTINCT m')
            ->from('DevlabsSportifyBundle:Match', 'm')
            ->join('m.predictions', 'p')
            ->where('p.scoreAdded IS NULL OR p.scoreAdded = 0')
            ->andWhere('m.homeGoals IS NOT NULL AND m.awayGoals IS NOT NULL')
            ->orderBy('m.id')
            ->getQuery()
            ->getResult();

        $result = array();

        /**
         * Iterate the query result array
         * and set the item key to be the match id
         */
        foreach ($queryResult as $match) {
            $result[$match->getId()] = $match;
        }

        return $result;
    }

    /**
     * Method for getting a list of the matches which have final score
     *
     * @param Tournament $tournament
     * @return array
     */
    public function getFinishedByTournament(Tournament $tournament)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('DISTINCT m')
            ->from('DevlabsSportifyBundle:Match', 'm')
            ->where('m.homeGoals IS NOT NULL AND m.awayGoals IS NOT NULL')
            ->andWhere('m.tournamentId = :tournament_id')
            ->setParameter('tournament_id', $tournament->getId())
            ->orderBy('m.id')
            ->getQuery()
            ->getResult();
    }

    /**
     * Method for getting a list of upcoming matches
     *
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getUpcoming($dateFrom, $dateTo)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('m')
            ->from('DevlabsSportifyBundle:Match', 'm')
            ->where('m.datetime >= :date_from AND m.datetime <= :date_to')
            ->orderBy('m.datetime')
            ->addOrderBy('m.homeTeam')
            ->setParameters(array(
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ))
            ->getQuery()
            ->getResult();
    }
}
