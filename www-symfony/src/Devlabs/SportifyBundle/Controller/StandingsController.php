<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class StandingsController
 * @package Devlabs\SportifyBundle\Controller
 */
class StandingsController extends Controller
{
    /**
     * @Route("/standings/{tournament}",
     *     name="standings_index",
     *     defaults={
     *      "tournament" = "1"
     *     }
     * )
     */
    public function indexAction(Request $request, $tournament)
    {
        // Load the data for the current user into an object
        $user = $this->getUser();

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // use the selected tournament as object, based on id URL: {tournament} route parameter
        $tournamentSelected = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findOneById($tournament);

        // get all tournaments
        $tournamentsAll = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();

        // creating a form for user,tournament,date filter
        $formData = array();
        $filterForm = $this->createFormBuilder($formData)
            ->add('tournament_id', EntityType::class, array(
                'class' => 'DevlabsSportifyBundle:Tournament',
                'choices' => $tournamentsAll,
                'choice_label' => 'name',
                'label' => false,
                'data' => $tournamentSelected
            ))
            ->add('button', SubmitType::class, array('label' => 'Filter'))
            ->getForm();

        $filterForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $formData = $filterForm->getData();
            $tournamentChoice = $formData['tournament_id'];

            // reload the page with the chosen filter(s) applied (as url path params)
            return $this->redirectToRoute(
                'standings_index',
                array(
                    'tournament' => $tournamentChoice->getId()
                )
            );
        }

        // get scores standings for a given tournament
        $standings = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByTournamentOrderByPoints($tournamentSelected);

        // rendering the view and returning the response
        return $this->render(
            'DevlabsSportifyBundle:Standings:index.html.twig',
            array(
                'standings' => $standings,
                'filter_form' => $filterForm->createView()
            )
        );
    }
}
