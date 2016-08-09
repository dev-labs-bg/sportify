<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Devlabs\SportifyBundle\Entity\Tournament;

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

        $tournament = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findOneById(12);

        $dataUpdatesManager = $this->get('app.data_updates.manager');
        $dataUpdatesManager->setEntityManager($em);

        $dataUpdatesManager->updateTeamsByTournament($tournament);
        $dataUpdatesManager->updateFixtures();
    }
}
