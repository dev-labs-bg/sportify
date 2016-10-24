<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class ScoreRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class ScoreRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get a single score row by user and tournament id
     *
     * @param User $user
     * @param Tournament $tournament
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByUserAndTournament(User $user, Tournament $tournament)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('s')
            ->from('DevlabsSportifyBundle:Score', 's')
            ->where('s.userId = :user_id')
            ->andWhere('s.tournamentId = :tournament_id')
            ->setParameters(array('user_id' => $user->getId(), 'tournament_id' => $tournament->getId()))
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Get standings table (scores) for a given tournament
     * ordered by points, exactPredictionPercentage, username properties
     *
     * @param Tournament $tournament
     * @return array
     */
    public function getByTournamentOrderByPoints(Tournament $tournament)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('s')
            ->from('DevlabsSportifyBundle:Score', 's')
            ->join('s.userId', 'u')
            ->where('s.tournamentId = :tournament_id')
            ->orderBy('s.points', 'DESC')
            ->addOrderBy('s.exactPredictionPercentage', 'DESC')
            ->addOrderBy('u.username', 'ASC')
            ->setParameters(array('tournament_id' => $tournament->getId()))
            ->getQuery()
            ->getResult();
    }

    /**
     * Get standings table (scores) for a given tournament
     * ordered by the posNew (pos_new) property
     *
     * @param Tournament $tournament
     * @return array
     */
    public function getByTournamentOrderByPosNew(Tournament $tournament)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('s')
            ->from('DevlabsSportifyBundle:Score', 's')
            ->where('s.tournamentId = :tournament_id')
            ->orderBy('s.posNew', 'ASC')
            ->setParameters(array('tournament_id' => $tournament->getId()))
            ->getQuery()
            ->getResult();
    }

    /**
     * Get a given user's scores for all the tournaments he's joined
     *
     * @param User $user
     * @return array
     */
    public function getByUser(User $user)
    {
        $queryResult = $this->getEntityManager()->createQueryBuilder()
            ->select('s')
            ->from('DevlabsSportifyBundle:Score', 's')
            ->where('s.userId = :user_id')
            ->setParameters(array('user_id' => $user->getId()))
            ->getQuery()
            ->getResult();

        $result = array();

        /**
         * Iterate the query result array
         * and set the item key to be the tournament id
         */
        foreach ($queryResult as $score) {
            $result[$score->getTournamentId()->getId()] = $score;
        }

        return $result;
    }
}
