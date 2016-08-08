<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function indexAction()
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Load the data for the current user into an object
        $user = $this->getUser();

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        $dataUpdatesManager = $this->get('app.data_updates.manager');
        $dataUpdatesManager->setEntityManager($em);

        $dataUpdatesManager->updateFixtures();

        // test stuff
//        $footballAPI = $this->get('app.data_updates.fetchers.football_data_org');
//        $footballAPI->setApiToken('896fa7a2adc1473ba474c6eb4e66cb4c');
//        $data = $footballAPI->fetchFixturesByTournamentAndMatchDay(426,1);
//        $data = $footballAPI->fetchTeamsByTournament(426)->teams[17];
        var_dump($data);
    }
}
