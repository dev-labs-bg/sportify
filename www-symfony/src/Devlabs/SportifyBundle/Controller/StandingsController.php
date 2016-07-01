<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Form\FilterType;

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
        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // set default values to route parameters if they are 'empty'
        if ($tournament_id === 'empty') {
            $tournamentSelected = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->getFirst();
        } else {
            // use the selected tournament as object, based on id URL: {tournament} route parameter
            $tournamentSelected = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->findOneById($tournament_id);
        }

        // get all tournaments
        $tournamentsAll = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();

        // set the input form-data
        $formInputData = array();
        $formInputData['tournament']['data'] = ($request->request->get('filter')) ? null : $tournamentSelected;
        $formInputData['tournament']['choices'] = $tournamentsAll;

        // creating a form for tournament filter
        $formData = array();
        $filterForm = $this->createForm(FilterType::class, $formData, array(
            'fields'=> array('tournament'),
            'data' => $formInputData
        ));

        $filterForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $formData = $filterForm->getData();

            $tournamentChoice = $formData['tournament']->getId()->getId();

            // reload the page with the chosen filter(s) applied (as url path params)
            return $this->redirectToRoute(
                'standings_index',
                array('tournament_id' => $tournamentChoice)
            );
        }

        // get scores standings for a given tournament
        $allScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByTournamentOrderByPosNew($tournamentSelected);

        // get the user's tournaments position data
        $userScores = array();

        // if user is logged in, get his standings
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Load the data for the current user into an object
            $user = $this->getUser();

            // get scores standings for the current user
            $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
                ->getByUser($user);
        }

        $twig = $this->container->get('twig');
        $twig->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'DevlabsSportifyBundle:Standings:index.html.twig',
            array(
                'all_scores' => $allScores,
                'filter_form' => $filterForm->createView()
            )
        );
    }
}
