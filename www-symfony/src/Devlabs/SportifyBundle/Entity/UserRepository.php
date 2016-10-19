<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class UserRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get a list of all enabled users
     *
     * @return array
     */
    public function getAllEnabled()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('u')
            ->from('DevlabsSportifyBundle:User', 'u')
            ->where('u.enabled = 1')
            ->orderBy('u.email', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get a list of users which don't have a prediction for a given match
     *
     * @param Match $match
     * @return array
     */
    public function getNotPredictedByMatch(Match $match)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $usersWithPredictions = $qb
            ->select(['u.id'])
            ->from('DevlabsSportifyBundle:User', 'u')
            ->join('u.predictions', 'p')
            ->where('u.enabled = 1')
            ->andWhere('p.matchId = :match_id')
            ->orderBy('u.id', 'ASC')
            ->setParameter('match_id', $match->getId())
            ->getQuery()
            ->getArrayResult();

        $qb = $this->getEntityManager()->createQueryBuilder();

        // different query depending on if the previous query returned empty array or not
        if ($usersWithPredictions) {
            return $qb
                ->select('u')
                ->from('DevlabsSportifyBundle:User', 'u')
                ->join('u.scores', 's')
                ->where('u.enabled = 1')
                ->andWhere('s.tournamentId = :tournament_id')
                ->andWhere($qb->expr()->notIn('u.id', ':users_predicted'))
                ->orderBy('u.id', 'ASC')
                ->setParameters(array(
                    'users_predicted' => $usersWithPredictions,
                    'tournament_id' => $match->getTournamentId()
                ))
                ->getQuery()
                ->getResult();
        } else {
            return $qb
                ->select('u')
                ->from('DevlabsSportifyBundle:User', 'u')
                ->join('u.scores', 's')
                ->where('u.enabled = 1')
                ->andWhere('s.tournamentId = :tournament_id')
                ->orderBy('u.id', 'ASC')
                ->setParameters(array(
                    'tournament_id' => $match->getTournamentId()
                ))
                ->getQuery()
                ->getResult();
        }
    }
}
