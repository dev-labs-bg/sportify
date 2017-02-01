<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

/**
 * Class PredictionController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class PredictionController extends BaseApiController
{
    protected $repositoryName = 'DevlabsSportifyBundle:Prediction';

//    /**
//     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
//     */
//    public function getPredictionsAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $predictions = $em->getRepository('DevlabsSportifyBundle:Prediction')
//            ->findAll();
//
//        return $predictions;
//    }
//
//    /**
//     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
//     */
//    public function getPredictionAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $prediction = $em->getRepository('DevlabsSportifyBundle:Prediction')
//            ->findOneById($id);
//
//        return $prediction;
//    }
}
