<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class TournamentRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class TournamentRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get a list of the tournaments joined by user
     *
     * @param User $user
     * @return array
     */
    public function getJoined(User $user)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('t')
            ->from('DevlabsSportifyBundle:Tournament', 't')
            ->join('t.scores', 's')
            ->where('s.userId = :user_id')
            ->setParameters(array('user_id' => $user->getId()))
            ->getQuery()
            ->getResult();
    }

    /**
     * Get the first tournament from the DB
     *
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getFirst()
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('t')
            ->from('DevlabsSportifyBundle:Tournament', 't')
            ->setMaxResults(1);

        try {
            return $query->getQuery()->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
