<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminController extends Controller
{
    /**
     * @Route("/admin/index", name="admin_index")
     */
    public function indexAction()
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // continue only if user is part of Admin Users list, else redirect to Home
//        if (!in_array($user->getEmail(), $this->container->getParameter('admin.users'))) {
//            return $this->redirectToRoute('home');
//        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render('Admin/index.html.twig');
    }

    /**
     * @Route("/admin/data_updates", name="admin_data_updates")
     */
    public function dataUpdatesAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the filter helper service
        $adminHelper = $this->container->get('app.admin.helper');

        // create form for Data Update type select and handle it
        $form = $adminHelper->createDataUpdatesForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminHelper->actionOnDataUpdatesFormSubmit($form);

            return $this->redirectToRoute('admin_data_updates');
        }

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'Admin/data_updates.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/admin/api_mappings/{tournament_id}",
     *     name="admin_api_mappings",
     *     defaults={
     *      "tournament_id" = "empty"
     *     }
     * )
     */
    public function apiMappingAction(Request $request, $tournament_id)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $urlParams['tournament_id'] = $tournament_id;

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get all tournaments as source data for form choices
        $formSourceData['tournament_choices'] = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();

        /**
         * Set first joined tournament as selected if URL param is 'empty'
         * or get the tournament by the URL tournament_id value
         */
        $formSourceData['tournament_selected'] = ($tournament_id === 'empty')
            ? $formSourceData['tournament_choices'][0]
            : $em->getRepository('DevlabsSportifyBundle:Tournament')->findOneById($tournament_id);

        // get the filter helper service
        $filterHelper = $this->container->get('app.filter.helper');

        // set the fields for the filter form
        $fields = array('tournament');

        // set the input data for the filter form and create it
        $formInputData = $filterHelper->getFormInputData($request, $urlParams, $fields, $formSourceData);
        $filterForm = $filterHelper->createForm($fields, $formInputData);
        $filterForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $submittedParams = $filterHelper->actionOnFormSubmit($filterForm, $fields);

            return $this->redirectToRoute('admin_api_mappings', $submittedParams);
        }

        // get the filter helper service
        $adminHelper = $this->container->get('app.admin.helper');

        // get the ApiMapping object and buttonAction
        $apiMapping = $adminHelper->getApiMapping(
            $formSourceData['tournament_selected'],
            $this->container->getParameter('football_api.name')
        );
        $buttonAction = $adminHelper->getApiMappingButtonAction($apiMapping);

        // create form for ApiMapping form and handle it
        $form = $adminHelper->createApiMappingForm($apiMapping, $buttonAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminHelper->actionOnApiMappingFormSubmit($form);

            return $this->redirectToRoute('admin_api_mappings');
        }

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'Admin/api_mappings.html.twig',
            array(
                'filter_form' => $filterForm->createView(),
                'form' => $form->createView()
            )
        );

    }
}
