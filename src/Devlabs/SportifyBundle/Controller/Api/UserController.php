<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use Devlabs\SportifyBundle\Entity\User;
use Devlabs\SportifyBundle\Form\UserType;

/**
 * Class UserController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class UserController extends BaseApiController
{
    protected $entityName = 'User';
    protected $fqEntityClass = User::class;
    protected $repositoryName = 'DevlabsSportifyBundle:User';
    protected $fqEntityFormClass = UserType::class;

    /**
     * @return \FOS\RestBundle\View\View
     */
    public function cgetAction()
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->view(null, 401);
        }

        // if user is not admin show only their data
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->getAction($user->getId());
        }

        $objects = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findAll();

        return $this->view($objects, 200);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getAction($id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->view(null, 401);
        }

        // restrict normal user to be able to see only their data
        if (!$this->isGranted('ROLE_ADMIN') && $user->getId() != $id) {
            return $this->view(null, 401);
        }

        // skip repository lookup if user id is same as requested
        if ($user->getId() == $id) {
            return $this->view($user, 200);
        }

        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->view(null, 404);
        }

        return $this->view($object, 200);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getScoresAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        return $this->view($object->getScores(), 200);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getPredictionsAction($id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->view(null, 401);
        }

        // restrict normal user to be able to see only their data
        if (!$this->isGranted('ROLE_ADMIN') && $user->getId() != $id) {
            return $this->view(null, 401);
        }

        // skip repository lookup if user id is same as requested
        if ($user->getId() == $id) {
            return $this->view($user->getPredictions(), 200);
        }

        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->view(null, 404);
        }

        return $this->view($object->getPredictions(), 200);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getChamp_predictionsAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        return $this->view($object->getPredictionsChampion(), 200);
    }
}
