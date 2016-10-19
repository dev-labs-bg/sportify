<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class ScoreUpdater
 * @package Devlabs\SportifyBundle\Services
 */
class ScoreUpdater
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }


    /**
     * Sequentially do these calculations:
     *  - match prediction points
     *  - user exact prediction percentage
     *  - champion prediction points
     *  - user positions
     *
     * @return array
     */
    public function updateAll()
    {
        $tournamentsModified = array();

        // calculate points for users' match predictions
        $this->scoreMatchPredictions($tournamentsModified);

        // calculate and users' exact score prediction percentage
        $this->calculatePredictionPercentage($tournamentsModified);

        // calculate points for predicted champion
        $this->scoreChampionPredictions($tournamentsModified);

        // calculate the user positions in the modified tournaments
        $this->calculateUserPositions($tournamentsModified);

        return $tournamentsModified;
    }

    /**
     * Update user positions in a tournament, when a user joins or leaves
     *
     * @param $tournament_id
     */
    public function updateUserPositionsForTournament($tournament_id)
    {
        // get tournament
        $tournament = $this->em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findOneById($tournament_id);

        $tournamentsModified = array();
        $tournamentsModified[] = $tournament;

        // calculate the user positions in the modified tournaments
        $this->calculateUserPositions($tournamentsModified);
    }

    /**
     * Score users' Champion predictions where Champion team has been set
     *
     * @param $tournamentsModified
     */
    private function scoreChampionPredictions(&$tournamentsModified)
    {
        // get all tournaments
        $tournamentsAll = $this->em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();

        foreach ($tournamentsAll as $tournament) {
            // skip tournaments with no champion team set
            if ($tournament->getChampionTeamId() == null) continue;

            $championPredictions = $this->em->getRepository('DevlabsSportifyBundle:PredictionChampion')
                ->getNotScoredByTournament($tournament);

            foreach ($championPredictions as $champPrediction) {
                // get the points from the prediction
                $predictionPoints = $champPrediction->calculatePoints();

                /**
                 * Update the prediction in the DB
                 * by setting the points gained and the score_added flag to 1
                 */
                $champPrediction->setPoints($predictionPoints);
                $champPrediction->setScoreAdded('1');

                // prepare the queries
                $this->em->persist($champPrediction);

                /**
                 * If the predictions's gained points are more than 0 (zero)
                 * then update the user score for the prediction's tournament
                 */
                if ($predictionPoints > 0) {
                    $userScore = $this->em->getRepository('DevlabsSportifyBundle:Score')
                        ->findOneBy(array(
                            'userId' => $champPrediction->getUserId(),
                            'tournamentId' => $champPrediction->getTournamentId(),
                        ));
                    $userScore->updatePoints($predictionPoints);

                    if (!in_array($tournament, $tournamentsModified)) {
                        $tournamentsModified[] = $tournament;
                    }

                    // prepare the queries
                    $this->em->persist($userScore);
                }
            }
        }

        // execute the queries
        $this->em->flush();
    }

    /**
     * Method for scoring users' match predictions for already finished matches
     *
     * @param $tournamentsModified
     */
    private function scoreMatchPredictions(&$tournamentsModified)
    {
        /**
         * Get a list of the finished matches
         * for which there are NOT SCORED predictions
         */
        $matches = $this->em->getRepository('DevlabsSportifyBundle:Match')
            ->getFinishedNotScored();

        // get list of enabled users
        $users = $this->em->getRepository('DevlabsSportifyBundle:User')
            ->getAllEnabled();

        // iterate for each user
        foreach ($users as $user) {
            /**
             * Get the list of scores (tournament + points) for the user
             */
            $scores = $this->em->getRepository('DevlabsSportifyBundle:Score')
                ->getByUser($user);

            /**
             * Get a list of NOT SCORED predictions by the user
             * for matches with final score set
             */
            $predictions = $this->em->getRepository('DevlabsSportifyBundle:Prediction')
                ->getFinishedNotScored($user);

            // iterate on all of the user's predictions
            foreach ($predictions as &$prediction) {
                /**
                 * Get corresponding match for the current prediction
                 */
                $matchId = $prediction->getMatchId()->getId();
                $match = $matches[$matchId];

                // get the points from the prediction
                $predictionPoints = $prediction->calculatePoints($match);

                /**
                 * Update the prediction in the DB
                 * by setting the points gained and the score_added flag to 1
                 */
                $prediction->setPoints($predictionPoints);
                $prediction->setScoreAdded('1');

                // prepare the queries
                $this->em->persist($prediction);

                /**
                 * If the predictions's gained points are more than 0 (zero)
                 * then update the user score for the prediction's tournament
                 */
                if ($predictionPoints > 0) {
                    $tournament = $match->getTournamentId();
                    $scores[$tournament->getId()]->updatePoints($predictionPoints);

                    if (!in_array($tournament, $tournamentsModified)) {
                        $tournamentsModified[] = $tournament;
                    }

                    // prepare the queries
                    $this->em->persist($scores[$tournament->getId()]);
                }
            }
        }

        // execute the queries
        $this->em->flush();
    }

    /**
     * Method for calculating the users' exact score prediction percentage
     *
     * @param $tournamentsModified
     */
    private function calculatePredictionPercentage(&$tournamentsModified)
    {
        foreach ($tournamentsModified as $tournament) {
            $scores = $this->em->getRepository('DevlabsSportifyBundle:Score')
                ->getByTournamentOrderByPoints($tournament);

            $matchesFinished = $this->em->getRepository('DevlabsSportifyBundle:Match')
                ->getFinishedByTournament($tournament);

            $matchCount = count($matchesFinished);

            foreach ($scores as &$score) {
                $user = $score->getUserId();
                $predictionsExact = $this->em->getRepository('DevlabsSportifyBundle:Prediction')
                    ->getExactPredictionsByUserAndTournament($user, $tournament);

                $predictionCount = count($predictionsExact);
                $exactPercentage = (int) (100 * $predictionCount / $matchCount);

                $score->setExactPredictionPercentage($exactPercentage);

                // prepare the queries
                $this->em->persist($score);
            }
        }

        // execute the queries
        $this->em->flush();
    }

    /**
     * Method for recalculation of the user positions in each of the modified tournaments
     *
     * @param $tournamentsModified
     */
    private function calculateUserPositions(&$tournamentsModified)
    {
        foreach ($tournamentsModified as $tournament) {
            $scores = $this->em->getRepository('DevlabsSportifyBundle:Score')
                ->getByTournamentOrderByPoints($tournament);

            $position = 0;
            foreach ($scores as &$score) {
                $position = $position + 1;
                $previousPosition = $score->getPosNew();

                $score->setPosOld($previousPosition);
                $score->setPosNew($position);

                // prepare the queries
                $this->em->persist($score);
            }
        }

        // execute the queries
        $this->em->flush();
    }
}