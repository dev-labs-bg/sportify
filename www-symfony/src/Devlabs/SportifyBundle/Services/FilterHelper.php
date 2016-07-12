<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Form\FilterType;

/**
 * Class FilterHelper
 * @package Devlabs\SportifyBundle\Services
 */
class FilterHelper
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Method for setting EntityManager
     * by passing in an ObjectManager object
     *
     * @param ObjectManager $em
     * @return $this
     */
    public function setEntityManager(ObjectManager $em)
    {
        $this->em = $em;

        return $this;
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
    public function getFormInputData($request, $user, $urlParams, $fields)
    {
        $formInputData = array();

        if (in_array('tournament', $fields)) {
            // use the selected tournament as object, based on id URL: {tournament} route parameter
            $tournamentSelected = ($urlParams['tournament_id'] === 'all')
                ? null
                : $this->em->getRepository('DevlabsSportifyBundle:Tournament')->findOneById($urlParams['tournament_id']);

            // get user's joined tournaments
            $tournamentsJoined = $this->em->getRepository('DevlabsSportifyBundle:Tournament')
                ->getJoined($user);

            $formInputData['tournament']['data'] = ($request->request->get('filter')) ? null : $tournamentSelected;
            $formInputData['tournament']['choices'] = $tournamentsJoined;
        }

        if (in_array('user', $fields)) {
            // use the selected user as object, based on id URL: {user_id} route parameter
            $userSelected = $this->em->getRepository('DevlabsSportifyBundle:User')
                ->findOneById($urlParams['user_id']);

            // get list of enabled users
            $usersEnabled = $this->em->getRepository('DevlabsSportifyBundle:User')
                ->getAllEnabled();

            $formInputData['user']['data'] = ($request->request->get('filter')) ? null : $userSelected;
            $formInputData['user']['choices'] = $usersEnabled;
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