<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Entity\User;
use Devlabs\SportifyBundle\Entity\Prediction;
use Devlabs\SportifyBundle\Form\PredictionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class PredictionController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class PredictionController extends BaseApiController
{
    protected $entityName = 'Prediction';
    protected $fqEntityClass = Prediction::class;
    protected $repositoryName = 'DevlabsSportifyBundle:Prediction';
    protected $fqEntityFormClass = PredictionType::class;

    /**
     * Get all predictions for all users (ADMIN only)
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function cgetAllusersAction(Request $request)
    {
        // get an array of all the query string key-value pairs
        $params = $request->query->all();

        // get all user predictions (by passing in an 'empty' user object)
        $objects = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findFiltered(new User(), $params);

        return $this->view($objects, 200);
    }

    /**
     * Get all predictions of requesting user
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
        // if user is not auth, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        // get an array of all the query string key-value pairs
        $params = $request->query->all();

        // get user's predictions
        $objects = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findFiltered($user, $params);

        return $this->view($objects, 200);
    }

    /**
     * Get a prediction by id
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

        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        // restrict normal user to be able to see only their data
        if (!$this->isGranted('ROLE_ADMIN') && $user != $object->getUserId()) {
            return $this->view(null, 403);
        }

        return $this->view($object, 200);
    }

    /**
     * Create a new prediction
     *
     * @ApiDoc(
     *     statusCodes = {
     *      201 = "Returned when resource successfully created",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postAction(Request $request)
    {
        return parent::postAction($request);
    }

    /**
     * Modify or create a new prediction by given id
     *
     * @ApiDoc(
     *     statusCodes = {
     *      201 = "Returned when resource successfully created",
     *      204 = "Returned when resource successfully modified",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, $id)
    {
        return parent::putAction($request, $id);
    }

    /**
     * Modify a prediction by id
     *
     * @ApiDoc(
     *     statusCodes = {
     *      204 = "Returned when resource successfully modified",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function patchAction(Request $request, $id)
    {
        return parent::patchAction($request, $id);
    }

    /**
     * Delete a prediction by id
     *
     * @ApiDoc(
     *     statusCodes = {
     *      204 = "Returned when resource successfully deleted",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }
}
