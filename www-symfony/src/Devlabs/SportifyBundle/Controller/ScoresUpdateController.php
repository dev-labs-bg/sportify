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

        // Get the ScoreUpdater service and update all scores
        $scoresUpdater = $this->get('app.score_updater');
        $scoresUpdater->updateAll();

        // Get instance of the Slack service and send notification
        $slack = $this->get('app.slack');
        $slack->setUrl('https://hooks.slack.com/services/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');
        $slack->setChannel('#sportify');
        $slack->setText('<@channel>: Match results and standings updated.');
        $slack->post();

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

        // Get the ScoreUpdater service and update user positions in tournament
        $scoresUpdater = $this->get('app.score_updater');
        $scoresUpdater->updateUserPositionsForTournament($tournament_id);

        // redirect to the Home page
        return $this->redirectToRoute('home');
    }
}
