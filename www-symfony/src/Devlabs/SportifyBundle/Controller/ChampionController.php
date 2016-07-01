<?php

namespace Devlabs\SportifyBundle\Controller;

use Devlabs\SportifyBundle\Entity\PredictionChampion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Form\FilterType;
use Devlabs\SportifyBundle\Form\ChampionSelectType;

/**
 * Class ChampionController
 * @package Devlabs\SportifyBundle\Controller
 */
class ChampionController extends Controller
{
    /**
     * @Route("/champion/{tournament_id}",
     *     name="champion_index",
     *     defaults={
     *      "tournament_id" = "empty"
     *     }
     * )
     */
    public function indexAction(Request $request, $tournament_id)
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Load the data for the current user into an object
        $user = $this->getUser();

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get joined tournaments
        $tournamentsJoined = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->getJoined($user);

        // set default values to route parameters if they are 'empty'
        if ($tournament_id === 'empty') {
            $tournamentSelected = $tournamentsJoined[0];
        } else {
            // use the selected tournament as object, based on id URL: {tournament} route parameter
            $tournamentSelected = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->findOneById($tournament_id);
        }

        // set the input form-data
        $formInputData = array();
        $formInputData['tournament']['data'] = ($request->request->get('filter')) ? null : $tournamentSelected;
        $formInputData['tournament']['choices'] = $tournamentsJoined;

        // creating a form for the tournament filter
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
                'champion_index',
                array('tournament_id' => $tournamentChoice)
            );
        }

        // get a list of teams for the selected tournament
        $teams = $em->getRepository('DevlabsSportifyBundle:Team')
            ->findByTournamentId($tournamentSelected);

        // get user's champion prediction
        $predictionChampion = $em->getRepository('DevlabsSportifyBundle:PredictionChampion')
            ->getByUserAndTournament($user, $tournamentSelected);

        $buttonAction = 'BET';

        // determine if the user has already set a champion prediction
        if ($predictionChampion === null) {
            $teamSelected = $em->getRepository('DevlabsSportifyBundle:Team')
                ->getFirstByTournament($tournamentSelected);
        } else {
            $teamSelected = $predictionChampion->getTeamId();
            $buttonAction = 'EDIT';
        }

        // set the deadline for champion prediction
        $deadline = '2016-06-16 16:00';
        $disabledAttribute = false;

        //if bet champion deadline is met, set disabled attribute to true
        if ((time() >= strtotime($deadline))) $disabledAttribute = true;

        // set the input form-data
        $formInputData = array();
        $formInputData['team']['data'] = $teamSelected;
        $formInputData['team']['choices'] = $teams;

        // creating the form for selecting the champion team
        $championForm = $this->createForm(ChampionSelectType::class, $formData, array(
            'data' => $formInputData,
            'button_action' => $buttonAction
        ));

        $championForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($championForm->isSubmitted() && $championForm->isValid()) {
            // get the team selected via the form
            $formData = $championForm->getData();
//            var_dump($formData);die;
            $teamChoice = $formData['team']->getId();

            // reload the page is form is submitted but deadline has passed
            if ($disabledAttribute)
                // clear the submitted POST data and reload the page
                return $this->redirectToRoute(
                    'champion_index',
                    array('tournament_id' => $tournamentSelected->getId())
                );

            // prepare the PredictionChampion object (new or modified one) for persisting in DB
            if ($formData['action'] === 'BET') {
                $predictionChampion = new PredictionChampion();
                $predictionChampion->setUserId($user);
                $predictionChampion->setTournamentId($tournamentSelected);
                $predictionChampion->setTeamId($teamChoice);
            } elseif ($formData['action'] === 'EDIT') {
                $predictionChampion->setTeamId($teamChoice);
            }

            // prepare the queries
            $em->persist($predictionChampion);

            // execute the queries
            $em->flush();

            // reload the page with the chosen filter(s) applied (as url path params)
            return $this->redirectToRoute(
                'champion_index',
                array('tournament_id' => $tournamentSelected->getId())
            );
        }

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $twig = $this->container->get('twig');
        $twig->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'DevlabsSportifyBundle:Champion:index.html.twig',
            array(
                'filter_form' => $filterForm->createView(),
                'champion_form' => $championForm->createView(),
                'prediction_champion' => $predictionChampion,
                'deadline' => $deadline,
                'disabled_attribute' => $disabledAttribute,
                'button_action' => $buttonAction
            )
        );
    }
}
