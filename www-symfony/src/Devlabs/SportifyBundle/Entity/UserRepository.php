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
}
