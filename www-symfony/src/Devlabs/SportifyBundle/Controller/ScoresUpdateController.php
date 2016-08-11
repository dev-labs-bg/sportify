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
     * @Route("/scores/update/all",
     *     name="scores_update_all"
     * )
     */
    public function updateAllAction()
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Get the ScoreUpdater service and update all scores
        $scoresUpdater = $this->get('app.score_updater');
        $scoresUpdater->setEntityManager($em);
        $scoresUpdater->updateAll();

        // redirect to the Home page
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/scores/update/user-positions-tournament/{tournament_id}",
     *     name="scores_update_user_pos_tournament"
     * )
     */
    public function updateUserPositionsForTournamentAction($tournament_id)
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Get the ScoreUpdater service and update user positions in tournament
        $scoresUpdater = $this->get('app.score_updater');
        $scoresUpdater->setEntityManager($em);
        $scoresUpdater->updateUserPositionsForTournament($tournament_id);

        // redirect to the Home page
        return $this->redirectToRoute('home');
    }
}
