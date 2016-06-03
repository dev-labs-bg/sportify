<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class MatchRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class MatchRepository extends \Doctrine\ORM\EntityRepository
{
    private $notScored = array();
    private $alreadyScored = array();
    private $finishedNotScored = array();

    /**
     * Method for getting a list of the matches which have not been scored/finished yet
     *
     * @param User $user
     * @param $tournament_id
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getNotScored(User $user)
    {
        $this->notScored = array();

        return $this->getEntityManager()->createQueryBuilder()
            ->select('m')
            ->from('DevlabsSportifyBundle:Match', 'm')
            ->join('m.tournamentId', 't')
            ->join('t.scores', 's', 'WITH', 's.userId = :user_id')
            ->leftJoin('m.predictions', 'p', 'WITH', 'p.userId = :user_id')
            ->where('p.scoreAdded IS NULL OR p.scoreAdded = 0')
            ->andWhere('m.homeGoals IS NULL OR m.awayGoals IS NULL')
            ->setParameters(array('user_id' => $user->getId()))
            ->getQuery()
            ->getResult();
    }
}
