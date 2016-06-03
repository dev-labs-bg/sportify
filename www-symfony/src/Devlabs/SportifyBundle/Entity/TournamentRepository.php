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
        /**
         * Get a list of the tournaments joined by user
         */
        return $this->getEntityManager()->createQueryBuilder()
            ->select('t')
            ->from('DevlabsSportifyBundle:Tournament', 't')
            ->join('t.scores', 's')
            ->where('s.userId = :user_id')
            ->setParameters(array('user_id' => $user->getId()))
            ->getQuery()
            ->getResult();

//        $scores = $this->getEntityManager()->createQueryBuilder()
//            ->select('s')
//            ->from('DevlabsSportifyBundle:Score', 's')
//            ->where('s.userId = :user_id')
//            ->setParameters(array('user_id' => $user->getId()))
//            ->getQuery()
//            ->getResult();
//
//        $result = array();
//        foreach ($scores as $score) {
//            $result[] = $score->getTournamentId();
//        }
//
//        return $result;
    }
}