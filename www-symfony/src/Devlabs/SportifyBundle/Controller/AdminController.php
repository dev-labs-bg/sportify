<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Entity\ApiMapping;
use Devlabs\SportifyBundle\Entity\Tournament;
use Devlabs\SportifyBundle\Entity\Team;

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

        return $this->dataUpdatesActionTemplate($request, $updateType, $choices, $viewTemplate);
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

        return $this->dataUpdatesActionTemplate($request, $updateType, $choices, $viewTemplate);
    }

    /**
     * @Route("/admin/data_updates/teams", name="admin_data_updates_teams")
     */
    public function teamsUpdateAction(Request $request)
    {
        $updateType = 'teams-all-tournaments';
        $choices = array();
        $viewTemplate = 'Admin/data_updates_teams.html.twig';

        return $this->dataUpdatesActionTemplate($request, $updateType, $choices, $viewTemplate);
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
    private function dataUpdatesActionTemplate(Request $request, $updateType, $choices, $viewTemplate)
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
     * @Route("/admin/api_mappings", name="admin_api_mappings")
     */
    public function apiMappingAction()
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

        // create Tournament forms
        $forms = $adminHelper->createApiMappingForms($tournaments);

        $apiTournaments = $this->container->get('app.data_updates.manager')->getTournaments();

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'Admin/api_mappings.html.twig',
            array(
                'tournaments' => $tournaments,
                'forms' => $forms,
                'api_tournaments' => $apiTournaments
            )
        );
    }

    /**
     * @Route("/admin/api_mappings/modify", name="admin_api_mappings_modify")
     */
    public function apiMappingModifyAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // redirect to admin/api_mappings page if the 'api_mapping' parameter is NOT set in the POST data
        if (!$request->request->get('api_mapping')) {
            return $this->redirectToRoute('admin_api_mappings');
        }

        // get the admin helper service
        $adminHelper = $this->container->get('app.admin.helper');

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // set the ApiMapping object based on whether it's new or existing one
        if ($request->request->get('api_mapping')['id']) {
            $apiMapping = $em->getRepository('DevlabsSportifyBundle:ApiMapping')
                ->findOneById($request->request->get('api_mapping')['id']);
        } else {
            $apiMapping = new ApiMapping();
            $apiMapping->setEntityId($request->request->get('api_mapping')['entityId']);
            $apiMapping->setEntityType($request->request->get('api_mapping')['entityType']);
            $apiMapping->setApiName($request->request->get('api_mapping')['apiName']);
        }

        $apiMapping->setApiObjectId($request->request->get('api_mapping')['apiObjectId']);

        $buttonAction = $request->request->get('api_mapping')['action'];

        $form = $adminHelper->createApiMappingForm($apiMapping, $buttonAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminHelper->actionOnEntityFormSubmit($form);
        }

        // clear the submitted POST data and reload the page
        return $this->redirectToRoute('admin_api_mappings');
    }

    /**
     * @Route("/admin/tournaments", name="admin_tournaments")
     */
    public function tournamentsAction()
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

        // create Tournament forms
        $forms = $adminHelper->createTournamentForms($tournaments);

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
            $adminHelper->actionOnEntityFormSubmit($form);
        }

        // clear the submitted POST data and reload the page
        return $this->redirectToRoute('admin_tournaments');
    }

    /**
     * @Route("/admin/teams", name="admin_teams")
     */
    public function teamsAction()
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the filter helper service
        $adminHelper = $this->container->get('app.admin.helper');

        // get all teams
        $teams = $em->getRepository('DevlabsSportifyBundle:Team')
            ->findAll();

        // add an 'empty' placeholder for a new tournament to be created
        $teams['new'] = new Team();

        // create Team forms
        $forms = $adminHelper->createTeamForms($teams);

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'Admin/teams.html.twig',
            array(
                'teams' => $teams,
                'forms' => $forms
            )
        );
    }

    /**
     * @Route("/admin/teams/modify", name="admin_teams_modify")
     */
    public function teamModifyAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // redirect to admin/teams page if the 'team_entity' parameter is NOT set in the POST data
        if (!$request->request->get('team_entity')) {
            return $this->redirectToRoute('admin_teams');
        }

        // get the admin helper service
        $adminHelper = $this->container->get('app.admin.helper');

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // set the team object based on whether it's new or existing one
        if ($request->request->get('team_entity')['id']) {
            $team = $em->getRepository('DevlabsSportifyBundle:Team')
                ->findOneById($request->request->get('team_entity')['id']);
        } else {
            $team = new Team();
        }

        $team->setName($request->request->get('team_entity')['name']);

        $buttonAction = $request->request->get('team_entity')['action'];

        $form = $adminHelper->createTeamForm($team, $buttonAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminHelper->actionOnEntityFormSubmit($form);
        }

        // clear the submitted POST data and reload the page
        return $this->redirectToRoute('admin_teams');
    }
}
