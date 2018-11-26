<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Class StandingsController
 * @package Devlabs\SportifyBundle\Controller
 */
class StandingsController extends Controller
{
    /**
     * @Route("/standings/{tournament_id}",
     *     name="standings_index",
     *     defaults={
     *      "tournament_id" = "empty"
     *     }
     * )
     */
    public function indexAction(Request $request, $tournament_id)
    {
        $urlParams['tournament_id'] = $tournament_id;

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        /**
         * Get selected tournament by last selected (from Cookie) and URL param is 'empty',
         * or set to first from DB if 'tournament' Cookie is not set and URL param is 'empty',
         * or get the tournament by the URL tournament_id value
         */
        if ($tournament_id === 'empty') {
            $formSourceData['tournament_selected'] = ($request->cookies->has('tournament'))
                ? $em->getRepository('DevlabsSportifyBundle:Tournament')
                    ->findOneById($request->cookies->get('tournament'))
                : $em->getRepository('DevlabsSportifyBundle:Tournament')
                    ->getFirst();
        } else {
            $formSourceData['tournament_selected'] = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->findOneById($tournament_id);
        }

        /**
         * If expected data for 'tournament_selected' is not valid, get the first tournament.
         * (usually happens when invalid 'tournament id' is passed)
         */
        if (!$formSourceData['tournament_selected']) {
            $formSourceData['tournament_selected'] = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->getFirst();
        }

        // get all tournaments as source data for form choices
        $formSourceData['tournament_choices'] = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();

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

            return $this->redirectToRoute('standings_index', $submittedParams);
        }

        // init allScores and Response
        $allScores = array();
        $response = new Response();

        if ($formSourceData['tournament_selected']) {
            // get scores standings for a given tournament
            $allScores = $em->getRepository('DevlabsSportifyBundle:Score')
                ->getByTournamentOrderByPosNew($formSourceData['tournament_selected']);

            // set cookie for tournament selected with 90-day expire period
            $response->headers->setCookie(new Cookie(
                'tournament',
                $formSourceData['tournament_selected']->getId(),
                time() + (3600 * 24 * 90)
            ));
        }

        // if user is logged in, get their standings and set them as global Twig var
        if (is_object($user = $this->getUser())) {
            $this->get('app.twig.helper')->setUserScores($user);
        }

        // rendering the view and returning the response
        return $this->render(
            'Standings/index.html.twig',
            array(
                'all_scores' => $allScores,
                'filter_form' => $filterForm->createView()
            ),
            $response
        );
    }

    /**
     * @Route("/standing/{tournament_id}",
     *     name="standing_index",
     *     defaults={
     *      "tournament_id" = "empty"
     *     }
     * )
     */
    public function index2Action(Request $request, $tournament_id)
    {
        $urlParams['tournament_id'] = $tournament_id;

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        /**
         * Get selected tournament by last selected (from Cookie) and URL param is 'empty',
         * or set to first from DB if 'tournament' Cookie is not set and URL param is 'empty',
         * or get the tournament by the URL tournament_id value
         */
        if ($tournament_id === 'empty') {
            $formSourceData['tournament_selected'] = ($request->cookies->has('tournament'))
                ? $em->getRepository('DevlabsSportifyBundle:Tournament')
                    ->findOneById($request->cookies->get('tournament'))
                : $em->getRepository('DevlabsSportifyBundle:Tournament')
                    ->getFirst();
        } else {
            $formSourceData['tournament_selected'] = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->findOneById($tournament_id);
        }

        /**
         * If expected data for 'tournament_selected' is not valid, get the first tournament.
         * (usually happens when invalid 'tournament id' is passed)
         */
        if (!$formSourceData['tournament_selected']) {
            $formSourceData['tournament_selected'] = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->getFirst();
        }

        // get all tournaments as source data for form choices
        $formSourceData['tournament_choices'] = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();

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

            return $this->redirectToRoute('standings_index', $submittedParams);
        }

        // init allScores and Response
        $allScores = array();
        $response = new Response();

        if ($formSourceData['tournament_selected']) {
            // get scores standings for a given tournament
            $allScores = $em->getRepository('DevlabsSportifyBundle:Score')
                ->getByTournamentOrderByPosNew($formSourceData['tournament_selected']);

            // set cookie for tournament selected with 90-day expire period
            $response->headers->setCookie(new Cookie(
                'tournament',
                $formSourceData['tournament_selected']->getId(),
                time() + (3600 * 24 * 90)
            ));
        }

        // if user is logged in, get their standings and set them as global Twig var
        if (is_object($user = $this->getUser())) {
            $this->get('app.twig.helper')->setUserScores($user);
        }

        // rendering the view and returning the response
        return $this->render(
            'Standings/index2.html.twig',
            array(
                'all_scores' => $allScores,
                'filter_form' => $filterForm->createView()
            ),
            $response
        );
    }
}
