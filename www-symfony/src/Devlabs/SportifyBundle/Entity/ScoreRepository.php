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

    public function getByTournamentOrderByPoints(Tournament $tournament)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('s')
            ->from('DevlabsSportifyBundle:Score', 's')
            ->where('s.tournamentId = :tournament_id')
            ->orderBy('s.points', 'DESC')
            ->setParameters(array('tournament_id' => $tournament->getId()))
            ->getQuery()
            ->getResult();
    }
}
