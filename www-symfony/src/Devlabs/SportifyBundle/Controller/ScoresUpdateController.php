<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ScoresUpdateController
 * @package Devlabs\SportifyBundle\Controller
 */
class ScoresUpdateController extends Controller
{
    /**
     * @Route("/scores/update",
     *     name="scores_update"
     * )
     */
    public function updateAction()
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        $tournamentsModified = array();

        /**
         * Get a list of the finished matches
         * for which there are NOT SCORED predictions
         */
        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getFinishedNotScored();

        // get list of enabled users
        $users = $em->getRepository('DevlabsSportifyBundle:User')
            ->getAllEnabled();

        // iterate for each user
        foreach ($users as $user) {
            /**
             * Get the list of scores (tournament + points) for the user
             */
            $scores = $em->getRepository('DevlabsSportifyBundle:Score')
                ->getByUser($user);

            /**
             * Get a list of NOT SCORED predictions by the user
             * for matches with final score set
             */
            $predictions = $em->getRepository('DevlabsSportifyBundle:Prediction')
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
                $em->persist($prediction);

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
                    $em->persist($scores[$tournament->getId()]);
                }
            }
        }

        // execute the points update queries
        $em->flush();

        // recalculation of the user positions in each of the modified tournaments
        foreach ($tournamentsModified as $tournament) {
            $scores = $em->getRepository('DevlabsSportifyBundle:Score')
                ->getByTournamentOrderByPoints($tournament);

            $position = 0;
            foreach ($scores as &$score) {
                $position = $position + 1;
                $currentPosition = $score->getPosNew();

                $score->setPosOld($currentPosition);
                $score->setPosNew($position);

                // prepare the queries
                $em->persist($score);
            }
        }

        // execute the positions update queries
        $em->flush();

        // redirect to the Home page
        return $this->redirectToRoute('home');
    }
}
