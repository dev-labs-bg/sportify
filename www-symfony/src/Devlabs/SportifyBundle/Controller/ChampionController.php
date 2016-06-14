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

/**
 * Class ChampionController
 * @package Devlabs\SportifyBundle\Controller
 */
class ChampionController extends Controller
{
    /**
     * @Route("/champion/{tournament}",
     *     name="champion_index",
     *     defaults={
     *      "tournament" = "empty"
     *     }
     * )
     */
    public function indexAction(Request $request, $tournament)
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
        if ($tournament === 'empty') {
            $tournamentSelected = $tournamentsJoined[0];
        } else {
            // use the selected tournament as object, based on id URL: {tournament} route parameter
            $tournamentSelected = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->findOneById($tournament);
        }

        // creating a form for the tournament filter
        $formData = array();
        $filterForm = $this->createFormBuilder($formData)
            ->add('tournament_id', EntityType::class, array(
                'class' => 'DevlabsSportifyBundle:Tournament',
                'choices' => $tournamentsJoined,
                'choice_label' => 'name',
                'label' => false,
                'data' => $tournamentSelected
            ))
            ->add('button', SubmitType::class, array('label' => 'FILTER'))
            ->getForm();

        $filterForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $formData = $filterForm->getData();

            $tournamentChoice = $formData['tournament_id'];

            // reload the page with the chosen filter(s) applied (as url path params)
            return $this->redirectToRoute(
                'champion_index',
                array('tournament' => $tournamentChoice->getId())
            );
        }

        // get a list of teams for the selected tournament
        $teams = $em->getRepository('DevlabsSportifyBundle:Team')
            ->findByTournamentId($tournamentSelected);

        // get user's champion prediction
        $predictionChampion = $em->getRepository('DevlabsSportifyBundle:PredictionChampion')
            ->getByUserAndTournament($user, $tournamentSelected);

        $teamSelected = ($predictionChampion === null)
            ? $em->getRepository('DevlabsSportifyBundle:Team')->getFirstByTournament($tournamentSelected)
            : $predictionChampion->getTeamId();

        $championForm = $this->createFormBuilder($formData)
            ->add('team_id', EntityType::class, array(
                'class' => 'DevlabsSportifyBundle:Team',
                'choices' => $teams,
                'choice_label' => 'name',
                'label' => false,
                'data' => $teamSelected
            ))
            ->add('button', SubmitType::class, array('label' => 'SELECT'))
            ->getForm();

        $championForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($championForm->isSubmitted() && $championForm->isValid()) {
            $formData = $championForm->getData();

            $teamChoice = $formData['team_id'];

            // reload the page with the chosen filter(s) applied (as url path params)
            return $this->redirectToRoute(
                'champion_index',
                array('tournament' => $tournamentSelected->getId())
            );
        }

        // rendering the view and returning the response
        return $this->render(
            'DevlabsSportifyBundle:Champion:index.html.twig',
            array(
                'filter_form' => $filterForm->createView(),
                'champion_form' => $championForm->createView()
            )
        );
    }
}
