<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Entity\User;
use Devlabs\SportifyBundle\Form\UserType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * Get all resources of this type
     *
     * @ApiDoc(
     *     resource=true,
     *     statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function cgetAction(Request $request)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
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
     * Get a resource by id
     *
     * @ApiDoc(
     *     statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getAction($id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        // restrict normal user to be able to see only their data
        if (!$this->isGranted('ROLE_ADMIN') && $user->getId() != $id) {
            return $this->view(null, 403);
        }

        // skip repository lookup if user id is same as requested
        if ($user->getId() == $id) {
            return $this->view($user, 200);
        }

        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->view($object, 200);
    }

    /**
     * Get user's scores
     *
     * @ApiDoc(
     *     statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getScoresAction($id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        // restrict normal user to be able to see only their data
        if (!$this->isGranted('ROLE_ADMIN') && $user->getId() != $id) {
            return $this->view(null, 403);
        }

        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->view($object->getScores(), 200);
    }

    /**
     * Get user's predictions
     *
     * @ApiDoc(
     *     statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getPredictionsAction($id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        // restrict normal user to be able to see only their data
        if (!$this->isGranted('ROLE_ADMIN') && $user->getId() != $id) {
            return $this->view(null, 403);
        }

        // skip repository lookup if user id is same as requested
        if ($user->getId() == $id) {
            return $this->view($user->getPredictions(), 200);
        }

        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->view($object->getPredictions(), 200);
    }

    /**
     * Get user's predictions for champion
     *
     * @ApiDoc(
     *     statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getChamp_predictionsAction($id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->view($object->getPredictionsChampion(), 200);
    }
}
