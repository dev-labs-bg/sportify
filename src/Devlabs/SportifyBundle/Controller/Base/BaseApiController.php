<?php

namespace Devlabs\SportifyBundle\Controller\Base;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class BaseApiController
 * @package Devlabs\SportifyBundle\Controller\Base
 */
abstract class BaseApiController extends FOSRestController implements ClassResourceInterface
{
    protected $entityName = 'Model';
    protected $fqEntityClass = Model::class;
    protected $repositoryName = 'DevlabsSportifyBundle:Model';
    protected $fqEntityFormClass = ModelType::class;

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
     * @return \FOS\RestBundle\View\View
     */
    public function cgetAction()
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        $em = $this->getDoctrine()->getManager();

        $objects = $em->getRepository($this->repositoryName)
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

        $em = $this->getDoctrine()->getManager();

        $object = $em->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->view($object, 200);
    }

    /**
     * Create a new resource of this type
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postAction(Request $request)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        $em = $this->getDoctrine()->getManager();

        $object = new $this->fqEntityClass();

        return $this->processForm(
            $request,
            $em,
            $object,
            'POST',
            201
        );
    }

    /**
     * Modify or create a new resource of this type by given id
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, $id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        $em = $this->getDoctrine()->getManager();

        $object = $em->getRepository($this->repositoryName)
            ->findOneById($id);

        $statusCode = 204;

        if (!is_object($object)) {
            $object = new $this->fqEntityClass();
            $statusCode = 201;
        }

        return $this->processForm(
            $request,
            $em,
            $object,
            'PUT',
            $statusCode
        );
    }

    /**
     * Modify a resource of this type by id
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function patchAction(Request $request, $id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        $em = $this->getDoctrine()->getManager();

        $object = $em->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->processForm(
            $request,
            $em,
            $object,
            'PATCH',
            204
        );
    }

    /**
     * Delete a resource of this type by id
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction($id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        $em = $this->getDoctrine()->getManager();

        $object = $em->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        // restrict normal user to be able to edit only their data
        if (method_exists($object, 'getUserId')
            && $user != $object->getUserId()
            && !$this->isGranted('ROLE_ADMIN'))
        {
            return $this->view(null, 403);
        }

        $em->remove($object);
        $em->flush();

        return $this->view(
            null,
            204
        );
    }

    /**
     * Create and process Entity form used for POST, PUT, PATCH requests
     *
     * @param Request $request
     * @param ObjectManager $em
     * @param $object
     * @param $method
     * @param int $statusCode
     * @return \FOS\RestBundle\View\View
     */
    protected function processForm(
        Request $request,
        ObjectManager $em,
        $object,
        $method,
        $statusCode = 200
    ) {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        $form = $this->createForm(
            $this->fqEntityFormClass,
            $object,
            array(
                'csrf_protection' => false,
                'method' => $method
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            // restrict normal user to be able to edit only their data
            if (method_exists($object, 'getUserId')
                && $user != $object->getUserId()
                && !$this->isGranted('ROLE_ADMIN'))
            {
                return $this->view(null, 403);
            }

            $em->persist($object);
            $em->flush();

            return $this->view($object, $statusCode);
        }

        return $this->view($form, 400);
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    protected function getUnauthorizedView()
    {
        return $this->view(null, 401);
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    protected function getNotFoundView()
    {
        return $this->view(null, 404);
    }
}