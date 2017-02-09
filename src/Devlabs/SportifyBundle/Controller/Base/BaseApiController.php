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