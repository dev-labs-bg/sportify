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

        // continue only if user has Admin access, else redirect to Home
        if ($user->getEmail() !== 'ceco@devlabs.bg') {
            return $this->redirectToRoute('home');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        $tournament = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findOneById(12);

        $dataUpdatesManager = $this->get('app.data_updates.manager');
        $dataUpdatesManager->setEntityManager($em);

//        $dataUpdatesManager->updateTeamsByTournament($tournament);

        // set dateFrom and dateTo to respectively today and 1 week on
        $dateFrom = date("Y-m-d");
        $dateTo = date("Y-m-d", time() + 604800);

        $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);
//        $dataUpdatesManager->updateFixtures('2016-07-09', '2016-07-10');

        return $this->redirectToRoute('home');
    }
}
