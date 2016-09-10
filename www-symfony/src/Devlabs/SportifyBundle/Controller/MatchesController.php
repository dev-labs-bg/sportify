<?php

namespace Devlabs\SportifyBundle\Controller;

use Devlabs\SportifyBundle\Entity\Prediction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MatchesController
 * @package Devlabs\SportifyBundle\Controller
 */
class MatchesController extends Controller
{
    /**
     * @Route("/matches/{action}/{tournament_id}/{date_from}/{date_to}",
     *     name="matches_index",
     *     defaults={
     *      "action" = "index",
     *      "tournament_id" = "empty",
     *      "date_from" = "empty",
     *      "date_to" = "empty"
     *     },
     *     requirements={
     *      "action" : "index"
     *     }
     * )
     */
    public function indexAction(Request $request, $tournament_id, $date_from, $date_to)
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Load the data for the current user into an object
        $user = $this->getUser();

        // get the matches helper service
        $matchesHelper = $this->container->get('app.matches.helper');

        // set default values to route parameters if they are 'empty'
        $urlParams = $matchesHelper->initUrlParams($tournament_id, $date_from, $date_to);

        $modifiedDateTo = date("Y-m-d", strtotime($urlParams['date_to']) + 86500);

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the filter helper service
        $filterHelper = $this->container->get('app.filter.helper');

        // set the fields for the filter form
        $fields = array('tournament', 'date_from', 'date_to');

        // set the input data for the filter form and create it
        $formSourceData = $filterHelper->getFormSourceData($user, $urlParams, $fields);
        $formInputData = $filterHelper->getFormInputData($request, $urlParams, $fields, $formSourceData);
        $filterForm = $filterHelper->createForm($fields, $formInputData);
        $filterForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $submittedParams = $filterHelper->actionOnFormSubmit($filterForm, $fields);

            return $this->redirectToRoute('matches_index', $submittedParams);
        }

        // get not finished matches and the user's predictions for them
        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getNotScored($user, $urlParams['tournament_id'], $urlParams['date_from'], $modifiedDateTo);
        $predictions = $em->getRepository('DevlabsSportifyBundle:Prediction')
            ->getNotScored($user, $urlParams['tournament_id'], $urlParams['date_from'], $modifiedDateTo);

        $matchForms = array();

        if ($matches) {
            $matchForms = $this->createMatchForms($request, $urlParams, $matchesHelper, $user, $matches, $predictions);
        }

        // get user standings and set them as global Twig var
        $this->get('app.twig.helper')->setUserScores($user);

        // rendering the view and returning the response
        return $this->render(
            'Matches/index.html.twig',
            array(
                'matches' => $matches,
                'predictions' => $predictions,
                'filter_form' => $filterForm->createView(),
                'match_forms' => $matchForms
            )
        );
    }

    /**
     * @Route("/matches/{action}/{tournament_id}/{date_from}/{date_to}",
     *     name="matches_bet",
     *     defaults={
     *      "action" = "bet",
     *      "tournament_id" = "empty",
     *      "date_from" = "empty",
     *      "date_to" = "empty"
     *     },
     *     requirements={
     *      "action" : "bet"
     *     }
     * )
     */
    public function betAction(Request $request, $tournament_id, $date_from, $date_to)
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // redirect to the matches main page if the 'prediction' parameter is NOT set in the POST data
        if (!$request->request->get('prediction')) {
            return $this->redirectToRoute('matches_index');
        }

        // Load the data for the current user into an object
        $user = $this->getUser();

        // get the matches helper service
        $matchesHelper = $this->container->get('app.matches.helper');

        // set default values to route parameters if they are 'empty'
        $urlParams = $matchesHelper->initUrlParams($tournament_id, $date_from, $date_to);

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the submitted form's match object
        $match = $em->getRepository('DevlabsSportifyBundle:Match')
            ->findOneById($request->request->get('prediction')['matchId']);

        // set the prediction object based on whether it's new or existing one
        if ($request->request->get('prediction')['id']) {
            $prediction = $em->getRepository('DevlabsSportifyBundle:Prediction')
                ->findOneById($request->request->get('prediction')['id']);
            $prediction->setHomeGoals($request->request->get('prediction')['homeGoals']);
            $prediction->setAwayGoals($request->request->get('prediction')['awayGoals']);
        } else {
            $prediction = new Prediction();
            $prediction->setMatchId($match);
            $prediction->setUserId($user);
            $prediction->setHomeGoals($request->request->get('prediction')['homeGoals']);
            $prediction->setAwayGoals($request->request->get('prediction')['awayGoals']);
        }

        $buttonAction = $request->request->get('prediction')['action'];

        $form = $matchesHelper->createForm($request, $urlParams, $match, $prediction, $buttonAction);

        if ($form->isSubmitted() && $form->isValid()) {
            // if the submitted form's match has started, clear the submitted POST data and reload the page
            if ($match->hasStarted())
                return $this->redirectToRoute('matches_index', $urlParams);

            $matchesHelper->actionOnFormSubmit($form);
        }

        // clear the submitted POST data and reload the page
        return $this->redirectToRoute('matches_index', $urlParams);
    }

    /**
     * @Route("/matches/betall",
     *     name="matches_betall")
     */
    public function betAllAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // redirect to the matches main page if the 'matches' array is NOT set in the POST data
        if (!$request->request->get('matches')) {
            return $this->redirectToRoute('matches_index');
        }

        // Load the data for the current user into an object
        $user = $this->getUser();

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        $formsArray = $request->request->get('matches');

        foreach ($formsArray as $submittedForm) {
            $match = $em->getRepository('DevlabsSportifyBundle:Match')
                ->findOneById($submittedForm['matchId']);

            // skip to next form if this match has started or data is invalid
            if ($match->hasStarted() ||
                !(is_numeric($submittedForm['homeGoals']) && $submittedForm['homeGoals'] >= 0) ||
                !(is_numeric($submittedForm['awayGoals']) && $submittedForm['awayGoals'] >= 0))
                continue;

            // prepare the Prediction object (new or modified one) for persisting in DB
            if ($submittedForm['action'] === 'BET') {
                $prediction = new Prediction();
                $prediction->setUserId($user);
                $prediction->setMatchId($match);
                $prediction->setHomeGoals($submittedForm['homeGoals']);
                $prediction->setAwayGoals($submittedForm['awayGoals']);
            } elseif ($submittedForm['action'] === 'EDIT') {
                $prediction = $em->getRepository('DevlabsSportifyBundle:Prediction')
                    ->getOneByUserAndMatch($user, $match);
                $prediction->setHomeGoals($submittedForm['homeGoals']);
                $prediction->setAwayGoals($submittedForm['awayGoals']);
            }

            // prepare the queries
            $em->persist($prediction);
        }

        // execute the queries
        $em->flush();

        // redirect to the matches main page
        return $this->redirectToRoute('matches_index');
    }

    /**
     * Method for creating prediction forms for a given set matches
     *
     * @param $request
     * @param $urlParams
     * @param $matchesHelper
     * @param $user
     * @param $matches
     * @param $predictions
     * @return array
     */
    private function createMatchForms($request, $urlParams, $matchesHelper, $user, $matches, $predictions)
    {
        $matchForms = array();

        // creating a form with BET/EDIT button for each match
        foreach ($matches as $match) {

            //if match has started set disabled to true
            if ($match->hasStarted()) $match->setDisabledAttribute();

            $prediction = $matchesHelper->getPrediction($user, $match, $predictions);
            $buttonAction = $matchesHelper->getPredictionButton($prediction);

            $form = $matchesHelper->createForm($request, $urlParams, $match, $prediction, $buttonAction);

            // create view for each form
            $form = $form->createView();
            $matchForms[$match->getId()] = $form;
        }

        return $matchForms;
    }
}
