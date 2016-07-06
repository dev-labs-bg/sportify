<?php

namespace Devlabs\SportifyBundle\Controller;

use Devlabs\SportifyBundle\Entity\Prediction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Devlabs\SportifyBundle\Form\FilterType;
use Devlabs\SportifyBundle\Form\PredictionType;

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

        // set default values to route parameters if they are 'empty'
        if ($tournament_id === 'empty') $tournament_id = 'all';
        if ($date_from === 'empty') $date_from = date("Y-m-d");
        if ($date_to === 'empty') $date_to = date("Y-m-d", time() + 1209600);

        $modifiedDateTo = date("Y-m-d", strtotime($date_to) + 86500);

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // use the selected tournament as object, based on id URL: {tournament} route parameter
        $tournamentSelected = ($tournament_id === 'all')
            ? null
            : $em->getRepository('DevlabsSportifyBundle:Tournament')->findOneById($tournament_id);

        // get joined tournaments
        $tournamentsJoined = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->getJoined($user);

        // set the input form-data
        $formInputData = array();
        $formInputData['date_from'] = $date_from;
        $formInputData['date_to'] = $date_to;
        $formInputData['tournament']['data'] = ($request->request->get('filter')) ? null : $tournamentSelected;
        $formInputData['tournament']['choices'] = $tournamentsJoined;

        // creating a form for the tournament and date filter
        $formData = array();
        $filterForm = $this->createForm(FilterType::class, $formData, array(
            'fields'=> array('tournament', 'date_from', 'date_to'),
            'data' => $formInputData
        ));

        $filterForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $formData = $filterForm->getData();

            $tournamentChoice = $formData['tournament']->getId()->getId();
            $dateFromChoice = $formData['date_from'];
            $dateToChoice = $formData['date_to'];

            // reload the page with the chosen filter(s) applied (as url path params)
            return $this->redirectToRoute(
                'matches_index',
                array(
                    'tournament_id' => $tournamentChoice,
                    'date_from' => $dateFromChoice,
                    'date_to' => $dateToChoice
                )
            );
        }

        $matchesHelper = $this->container->get('app.matches.helper');
        $matchesHelper->setEntityManager($em);

        // get not finished matches and the user's predictions for them
        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getNotScored($user, $tournament_id, $date_from, $modifiedDateTo);
        $predictions = $em->getRepository('DevlabsSportifyBundle:Prediction')
            ->getNotScored($user, $tournament_id, $date_from, $modifiedDateTo);

        $matchForms = array();

        if ($matches) {
            // creating a form with BET/EDIT button for each match
            foreach ($matches as $match) {

                //if match has started set disabled to true
                if ($match->hasStarted()) $match->setDisabledAttribute();

                $form = $matchesHelper->createForm($request, $match, $predictions);
                $matchForms[$match->getId()] = $form;

                if ($form->isSubmitted() && $form->isValid()) {
                    if ($match->hasStarted())
                        // clear the submitted POST data and reload the page
                        return $this->redirectToRoute(
                            'matches_index',
                            array(
                                'tournament_id' => $tournament_id,
                                'date_from' => $date_from,
                                'date_to' => $date_to
                            )
                        );

                    $matchesHelper->actionOnFormSubmit();

                    // clear the submitted POST data and reload the page
                    return $this->redirectToRoute(
                        'matches_index',
                        array(
                            'tournament_id' => $tournament_id,
                            'date_from' => $date_from,
                            'date_to' => $date_to
                        )
                    );
                }
            }

            // create view for each form
            foreach ($matchForms as &$form) {
                $form = $form->createView();
            }
        }

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $twig = $this->container->get('twig');
        $twig->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'DevlabsSportifyBundle:Matches:index.html.twig',
            array(
                'matches' => $matches,
                'predictions' => $predictions,
                'filter_form' => $filterForm->createView(),
                'match_forms' => $matchForms
            )
        );
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
}
