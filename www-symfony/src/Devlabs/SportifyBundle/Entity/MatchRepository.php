<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class MatchRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class MatchRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Method for getting a list of the matches which have not been scored/finished yet
     *
     * @param User $user
     * @param $tournament_id
     * @param $dateFrom
     * @param $dateTo
     * @return array
     */
    public function getNotScored(User $user, $tournamentId, $dateFrom, $dateTo)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('m')
            ->from('DevlabsSportifyBundle:Match', 'm')
            ->join('m.tournamentId', 't')
            ->join('t.scores', 's', 'WITH', 's.userId = :user_id')
            ->leftJoin('m.predictions', 'p', 'WITH', 'p.userId = :user_id')
            ->where('p.scoreAdded IS NULL OR p.scoreAdded = 0')
            ->andWhere('m.homeGoals IS NULL OR m.awayGoals IS NULL')
            ->andWhere('m.datetime >= :date_from AND m.datetime <= :date_to')
            ->orderBy('m.tournamentId')
            ->addOrderBy('m.datetime')
            ->addOrderBy('m.homeTeam')
            ->setParameters(array(
                'user_id' => $user->getId(),
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ));

        // prepare a different query, if a tournament is selected for filtering
        if ($tournamentId !== 'all') {
            $query->andWhere('m.tournamentId = :tournament_id')
                ->setParameter('tournament_id', $tournamentId);
        }

        return $query->getQuery()->getResult();
    }

    public function getAlreadyScored(User $user, $tournamentId, $dateFrom, $dateTo)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('m')
            ->from('DevlabsSportifyBundle:Match', 'm')
            ->join('m.tournamentId', 't')
            ->join('t.scores', 's', 'WITH', 's.userId = :user_id')
            ->leftJoin('m.predictions', 'p', 'WITH', 'p.userId = :user_id')
//            ->where('p.scoreAdded = 1')
            ->andWhere('m.homeGoals IS NOT NULL AND m.awayGoals IS NOT NULL')
            ->andWhere('m.datetime >= :date_from AND m.datetime <= :date_to')
            ->orderBy('m.tournamentId')
            ->addOrderBy('m.datetime')
            ->addOrderBy('m.homeTeam')
            ->setParameters(array(
                'user_id' => $user->getId(),
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ));

        // prepare a different query, if a tournament is selected for filtering
        if ($tournamentId !== 'all') {
            $query->andWhere('m.tournamentId = :tournament_id')
                ->setParameter('tournament_id', $tournamentId);
        }

        return $query->getQuery()->getResult();
    }
}
