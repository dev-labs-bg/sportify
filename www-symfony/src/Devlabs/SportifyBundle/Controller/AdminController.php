<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Entity\Tournament;

class AdminController extends Controller
{
    /**
     * @Route("/admin/{action}", name="admin_index",
     *     defaults={"action" = "index"},
     *     requirements={"action" : "index"})
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

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render('Admin/data_updates_index.html.twig');
    }

    /**
     * @Route("/admin/data_updates/match_fixtures", name="admin_data_updates_match_fixtures")
     */
    public function matchFixturesUpdateAction(Request $request)
    {
        $updateType = 'matches-fixtures';
        $choices = array(
            'Next 3 days' => 3,
            'Next 5 days' => 5,
            'Next 7 days' => 7,
            'Next 2 weeks' => 14
        );
        $viewTemplate = 'Admin/data_updates_match_fixtures.html.twig';

        return $this->dataUpdatesTemplate($request, $updateType, $choices, $viewTemplate);
    }

    /**
     * @Route("/admin/data_updates/match_results", name="admin_data_updates_match_results")
     */
    public function matchResultsUpdateAction(Request $request)
    {
        $updateType = 'matches-results';
        $choices = array(
            'Past 1 day' => 1,
            'Past 3 days' => 3,
            'Past 7 days' => 7,
            'Past 2 weeks' => 14
        );
        $viewTemplate = 'Admin/data_updates_match_results.html.twig';

        return $this->dataUpdatesTemplate($request, $updateType, $choices, $viewTemplate);
    }

    /**
     * @Route("/admin/data_updates/teams", name="admin_data_updates_teams")
     */
    public function teamsUpdateAction(Request $request)
    {
        $updateType = 'teams-all-tournaments';
        $choices = array();
        $viewTemplate = 'Admin/data_updates_teams.html.twig';

        return $this->dataUpdatesTemplate($request, $updateType, $choices, $viewTemplate);
    }

    /**
     * Template method for DataUpdates action methods
     *
     * @param Request $request
     * @param $updateType
     * @param $choices
     * @param $viewTemplate
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function dataUpdatesTemplate(Request $request, $updateType, $choices, $viewTemplate)
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
        $form = $adminHelper->createDataUpdatesForm($updateType, $choices);
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
            $viewTemplate,
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

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        $urlParams['tournament_id'] = $tournament_id;

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

        $apiTournaments = $this->container->get('app.data_updates.manager')->getTournaments();

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'Admin/api_mappings.html.twig',
            array(
                'filter_form' => $filterForm->createView(),
                'form' => $form->createView(),
                'api_tournaments' => $apiTournaments
            )
        );
    }

    /**
     * @Route("/admin/tournaments", name="admin_tournaments")
     */
    public function tournamentsAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the filter helper service
        $adminHelper = $this->container->get('app.admin.helper');

        // get all tournaments
        $tournaments = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();

        // add an 'empty' placeholder for a new tournament to be created
        $tournaments['new'] = new Tournament();

        $forms = array();

        // creating a form for each tournament
        foreach ($tournaments as $tournament) {
            // get buttonAction
            $buttonAction = $adminHelper->getTournamentButton($tournament);

            // create form for ApiMapping form and handle it
            $form = $adminHelper->createTournamentForm($tournament, $buttonAction);

            // create view for each tournament's form
            $forms[] = $form->createView();
        }

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'Admin/tournaments.html.twig',
            array(
                'tournaments' => $tournaments,
                'forms' => $forms
            )
        );
    }

    /**
     * @Route("/admin/tournaments/modify", name="admin_tournaments_modify")
     */
    public function tournamentModifyAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // redirect to admin/tournaments page if the 'tournament_entity' parameter is NOT set in the POST data
        if (!$request->request->get('tournament_entity')) {
            return $this->redirectToRoute('admin_tournaments');
        }

        // get the admin helper service
        $adminHelper = $this->container->get('app.admin.helper');

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // set the tournament object based on whether it's new or existing one
        if ($request->request->get('tournament_entity')['id']) {
            $tournament = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->findOneById($request->request->get('tournament_entity')['id']);
        } else {
            $tournament = new Tournament();
        }

        // create DateTime object from the datetime string in the POST request
        $startDate = \DateTime::createFromFormat('Y-m-d', $request->request->get('tournament_entity')['startDate']);
        $endDate = \DateTime::createFromFormat('Y-m-d', $request->request->get('tournament_entity')['endDate']);

        $tournament->setName($request->request->get('tournament_entity')['name']);
        $tournament->setStartDate($startDate);
        $tournament->setEndDate($endDate);
        $tournament->setNameShort($request->request->get('tournament_entity')['nameShort']);

        $buttonAction = $request->request->get('tournament_entity')['action'];

        $form = $adminHelper->createTournamentForm($tournament, $buttonAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminHelper->actionOnTournamentFormSubmit($form);
        }

        // clear the submitted POST data and reload the page
        return $this->redirectToRoute('admin_tournaments');
    }
}
