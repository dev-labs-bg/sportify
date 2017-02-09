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
            'POST'
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

        if (!is_object($object)) {
            $object = new $this->fqEntityClass();
        }

        return $this->processForm(
            $request,
            $em,
            $object,
            'PUT'
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

        return $this->processForm(
            $request,
            $em,
            $object,
            'PATCH'
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

        $em->remove($object);
        $em->flush();

        return array('status' => 'Deleted '.$this->entityName.' with id '.$id);
    }

    /**
     * Create and process Entity form used for POST, PUT, PATCH requests
     *
     * @param Request $request
     * @param ObjectManager $em
     * @param $object
     * @param $method
     * @return \Symfony\Component\Form\FormErrorIterator
     */
    protected function processForm(Request $request, ObjectManager $em, $object, $method)
    {
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

            return $object;
        }

        return $form->getErrors();
    }
}