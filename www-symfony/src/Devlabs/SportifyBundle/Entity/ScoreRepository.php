<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class ScoreRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class ScoreRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Method for getting a single score row by user and tournament id
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
     * Method for getting the standings table (scores) for a given tournament
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
            ->addOrderBy('u.email', 'ASC')
            ->setParameters(array('tournament_id' => $tournament->getId()))
            ->getQuery()
            ->getResult();
    }

    /**
     * Method for getting a given user's scores for all the tournaments he's joined
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
