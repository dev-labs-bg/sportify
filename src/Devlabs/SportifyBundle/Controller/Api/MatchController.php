<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

/**
 * Class MatchController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class MatchController extends BaseApiController
{
    protected $repositoryName = 'DevlabsSportifyBundle:Match';

//    /**
//     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
//     */
//    public function getMatchesAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
//            ->findAll();
//
//        return $matches;
//    }
//
//    /**
//     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
//     */
//    public function getMatchAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $match = $em->getRepository('DevlabsSportifyBundle:Match')
//            ->findOneById($id);
//
//        return $match;
//    }
}
