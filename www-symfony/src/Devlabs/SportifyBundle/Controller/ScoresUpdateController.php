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
     * @Route("/scores_update",
     *     name="scores_update_index"
     * )
     */
    public function indexAction(Request $request)
    {
        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        /**
         * Get a list of the finished matches
         * for which there are NOT SCORED predictions
         */
        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getFinishedNotScored();

        /**
         * Get a list of NOT SCORED predictions
         * for matches with final score set
         */
        $predictions = $em->getRepository('DevlabsSportifyBundle:Prediction')
            ->getFinishedNotScored();

        // get list of enabled users
        $users = $em->getRepository('DevlabsSportifyBundle:User')
            ->getAllEnabled();

        foreach ($matches as $match) {
            foreach ($users as $user) {
                $prediction = $predictions[$user->getId()][$match->getId()];

            }
        }

        // redirect to the Home page
        return $this->redirectToRoute('home');
    }
}
