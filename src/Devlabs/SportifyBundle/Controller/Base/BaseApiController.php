<?php

namespace Devlabs\SportifyBundle\Controller\Base;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

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

    public function cgetAction()
    {
        $em = $this->getDoctrine()->getManager();

        $objects = $em->getRepository($this->repositoryName)
            ->findAll();

        return $this->view($objects, 200);
    }

    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $object = $em->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->view(null, 404);
        }

        return $this->view($object, 200);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormErrorIterator
     */
    public function postAction(Request $request)
    {
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
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator
     */
    public function putAction(Request $request, $id)
    {
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
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator
     */
    public function patchAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $object = $em->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->view(null, 404);
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
     * @param $id
     * @return array
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $object = $em->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->view(null, 404);
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
            $em->persist($object);
            $em->flush();

            return $this->view($object, $statusCode);
        }

        return $this->view($form, 400);
    }
}