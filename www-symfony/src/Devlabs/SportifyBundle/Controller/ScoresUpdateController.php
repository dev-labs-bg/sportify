<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function updateAction(Request $request)
    {
        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

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
                    $tournamentId = $match->getTournamentId()->getId();
                    $scores[$tournamentId]->updatePoints($predictionPoints);

                    // prepare the queries
                    $em->persist($scores[$tournamentId]);
                }
            }
        }

        // execute the queries
        $em->flush();

        // redirect to the Home page
        return $this->redirectToRoute('home');
    }
}
