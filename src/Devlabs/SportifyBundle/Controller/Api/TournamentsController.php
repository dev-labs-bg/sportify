<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

/**
 * Class TournamentsController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class TournamentsController extends FOSRestController
{
    /**
     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
     */
    public function getTournamentsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tournaments = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();

        return $tournaments;
    }

    /**
     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
     */
    public function getTournamentAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $tournament = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findOneById($id);

        return $tournament;
    }
}
