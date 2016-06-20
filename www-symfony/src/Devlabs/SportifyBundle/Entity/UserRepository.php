<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class UserRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Method for getting a list of all enabled users
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

    public function getNotPredictedByMatch(Match $match)
    {
        $usersWithPredictions = $this->getEntityManager()->createQueryBuilder()
            ->select('u')
            ->from('DevlabsSportifyBundle:User', 'u')
            ->join('u.predictions', 'p')
            ->where('u.enabled = 1')
            ->andWhere('p.match_id = :match_id')
            ->orderBy('u.id', 'ASC')
            ->setParameter('tournament_id', $tournament->getId())
            ->getQuery()
            ->getResult();
    }
}
