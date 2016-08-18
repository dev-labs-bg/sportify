<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Devlabs\SportifyBundle\Form\FilterType;

/**
 * Class FilterHelper
 * @package Devlabs\SportifyBundle\Services
 */
class FilterHelper
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }

    /**
     * Method for getting the source data used for the input fields of the filter form
     *
     * @param $request
     * @param $user
     * @param $urlParams
     * @param $fields
     * @return array
     */
    public function getFormSourceData($user, $urlParams, $fields)
    {
        $formSourceData = array();

        if (in_array('tournament', $fields)) {
            // use the selected tournament as object, based on id URL: {tournament} route parameter
            $formSourceData['tournament_selected'] = ($urlParams['tournament_id'] === 'all')
                ? null
                : $this->em->getRepository('DevlabsSportifyBundle:Tournament')->findOneById($urlParams['tournament_id']);

            // get user's joined tournaments
            $formSourceData['tournament_choices'] = $this->em->getRepository('DevlabsSportifyBundle:Tournament')
                ->getJoined($user);
        }

        if (in_array('user', $fields)) {
            // use the selected user as object, based on id URL: {user_id} route parameter
            $formSourceData['user_selected'] = $this->em->getRepository('DevlabsSportifyBundle:User')
                ->findOneById($urlParams['user_id']);

            // get list of enabled users
            $formSourceData['user_choices'] = $this->em->getRepository('DevlabsSportifyBundle:User')
                ->getAllEnabled();
        }

        return $formSourceData;
    }

    /**
     * Method for getting the input data for the filter form
     *
     * @param $request
     * @param $user
     * @param $urlParams
     * @param $fields
     * @return array
     */
    public function getFormInputData($request, $urlParams, $fields, $formSourceData)
    {
        $formInputData = array();

        if (in_array('tournament', $fields)) {
            $formInputData['tournament']['data'] = ($request->request->get('filter')) ? null : $formSourceData['tournament_selected'];
            $formInputData['tournament']['choices'] = $formSourceData['tournament_choices'];
        }

        if (in_array('user', $fields)) {
            $formInputData['user']['data'] = ($request->request->get('filter')) ? null : $formSourceData['user_selected'];
            $formInputData['user']['choices'] = $formSourceData['user_choices'];
        }

        if (in_array('date_from', $fields)) {
            $formInputData['date_from'] = $urlParams['date_from'];
        }

        if (in_array('date_to', $fields)) {
            $formInputData['date_to'] = $urlParams['date_to'];
        }

        return $formInputData;
    }


    /**
     * Method for creating a Filter form
     *
     * @param $request
     * @param $urlParams
     * @param $match
     * @param $prediction
     * @param $buttonAction
     * @return mixed
     */
    public function createForm($fields, $formInputData)
    {
        $formData = array();

        $form = $this->container->get('form.factory')->create(FilterType::class, $formData, array(
            'fields' => $fields,
            'data' => $formInputData
        ));

        return $form;
    }

    /**
     * Method for executing actions after the filter form is submitted
     *
     * @param $form
     */
    public function actionOnFormSubmit($form, $fields)
    {
        $formData = $form->getData();
        $submittedParams = array();

        if (in_array('user', $fields)) {
            $submittedParams['user_id'] = $formData['user']->getId()->getId();
        }

        if (in_array('tournament', $fields)) {
            $submittedParams['tournament_id'] = $formData['tournament']->getId()->getId();
        }

        if (in_array('date_from', $fields)) {
            $submittedParams['date_from'] = $formData['date_from'];
        }

        if (in_array('date_from', $fields)) {
            $submittedParams['date_to'] = $formData['date_to'];
        }

        return $submittedParams;
    }
}