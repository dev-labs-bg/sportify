<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

/**
 * Class UsersController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class UsersController extends FOSRestController
{
    /**
     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
     */
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('DevlabsSportifyBundle:User')
            ->findAll();

        return $users;
    }

    /**
     * @ViewAnnotation(serializerEnableMaxDepthChecks=true)
     */
    public function getUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('DevlabsSportifyBundle:User')
            ->findOneById($id);

        return $user;
    }
}
