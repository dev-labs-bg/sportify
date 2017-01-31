<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

/**
 * Class MatchesController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class MatchesController extends FOSRestController
{
    /**
     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
     */
    public function getMatchesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->findAll();

        return $matches;
    }

    /**
     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
     */
    public function getMatchAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $match = $em->getRepository('DevlabsSportifyBundle:Match')
            ->findOneById($id);

        return $match;
    }
}
