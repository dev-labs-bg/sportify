<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class PredictionRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class PredictionRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Method for getting a list of the predictions for matches which have not been scored/finished yet
     *
     * @param User $user
     * @return array
     */
    public function getNotScored(User $user)
    {
        $queryResult = $this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('DevlabsSportifyBundle:Prediction', 'p')
            ->join('p.matchId', 'm')
            ->join('m.tournamentId', 't')
            ->join('t.scores', 's', 'WITH', 's.userId = :user_id')
            ->where('p.userId = :user_id')
            ->andWhere('p.scoreAdded IS NULL OR p.scoreAdded = 0')
            ->andWhere('m.homeGoals IS NULL OR m.awayGoals IS NULL')
            ->setParameters(array('user_id' => $user->getId()))
            ->getQuery()
            ->getResult();

        $result = array();

        /**
         * Iterate the query result array
         * and set the item key to be the match id
         */
        foreach ($queryResult as $prediction) {
            $result[$prediction->getMatchId()->getId()] = $prediction;
        }

        return $result;
    }
}
