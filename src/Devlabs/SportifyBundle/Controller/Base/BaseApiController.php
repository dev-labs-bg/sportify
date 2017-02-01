<?php

namespace Devlabs\SportifyBundle\Controller\Base;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;
use FOS\RestBundle\Routing\ClassResourceInterface;

/**
 * Class BaseApiController
 * @package Devlabs\SportifyBundle\Controller\Base
 */
abstract class BaseApiController extends FOSRestController implements ClassResourceInterface
{
    protected $repositoryName = 'DevlabsSportifyBundle:Model';

    /**
     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
     */
    public function cgetAction()
    {
        $em = $this->getDoctrine()->getManager();

        $objects = $em->getRepository($this->repositoryName)
            ->findAll();

        return $objects;
    }

    /**
     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
     */
    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $object = $em->getRepository($this->repositoryName)
            ->findOneById($id);

        return $object;
    }
}