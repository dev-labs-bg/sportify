<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class OAuthAccessTokenRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class OAuthAccessTokenRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get the last not expired access token for the user
     *
     * @param User $user
     * @return array
     */
    public function getLastNotExpired(User $user)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('at')
            ->from('DevlabsSportifyBundle:OAuthAccessToken', 'at')
            ->where('at.user = :user_id')
            ->andWhere('at.expiresAt > :current_timestamp')
            ->orderBy('at.expiresAt', 'DESC')
            ->setMaxResults(1)
            ->setParameters(array(
                'user_id' => $user->getId(),
                'current_timestamp' => time()
            ))
            ->getQuery()
            ->getOneOrNullResult();
    }
}
