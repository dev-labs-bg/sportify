<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class TournamentRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class TournamentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getJoined(User $user)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('t')
            ->from('DevlabsSportifyBundle:Tournament', 't')
            ->join('t.users', 'u')
            ->where('u.id = :user_id')
            ->setParameters(array('user_id' => $user->getId()))
            ->getQuery()
            ->getResult();
    }

    public function getNotJoined()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('t')
            ->from('DevlabsSportifyBundle:Tournament', 't')
            ->getQuery()
            ->getResult();
    }
}
