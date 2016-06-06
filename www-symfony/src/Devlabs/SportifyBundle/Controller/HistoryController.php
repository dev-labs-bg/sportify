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
 * Class HistoryController
 * @package Devlabs\SportifyBundle\Controller
 */
class HistoryController extends Controller
{
    /**
     * @Route("/history/{user_id}/{tournament}/{date_from}/{date_to}",
     *     name="history_index",
     *     defaults={
     *      "user_id" = "1",
     *      "tournament" = "all",
     *      "date_from" = "2016-01-01",
     *      "date_to" = "2021-12-31"
     *     }
     * )
     */
    public function indexAction(Request $request, $user_id, $tournament, $date_from, $date_to)
    {
        // Load the data for the current user into an object
        $user = $this->getUser();

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // use the selected user as object, based on id URL: {user_id} route parameter
        $userSelected = $em->getRepository('DevlabsSportifyBundle:User')
            ->findOneById($user_id);

        // get list of enabled users
        $usersEnabled = $em->getRepository('DevlabsSportifyBundle:User')
            ->getAllEnabled();

        // use the selected tournament as object, based on id URL: {tournament} route parameter
        $tournamentSelected = ($tournament === 'all')
            ? null
            : $em->getRepository('DevlabsSportifyBundle:Tournament')->findOneById($tournament);

        // get joined tournaments
        $tournamentsJoined = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->getJoined($user);

        // creating a form for user,tournament,date filter
        $formData = array();
        $filterForm = $this->createFormBuilder($formData)
            ->add('user', EntityType::class, array(
                'class' => 'DevlabsSportifyBundle:User',
                'choices' => $usersEnabled,
                'choice_label' => 'email',
                'label' => false,
                'data' => $userSelected
            ))
            ->add('tournament_id', EntityType::class, array(
                'class' => 'DevlabsSportifyBundle:Tournament',
                'choices' => $tournamentsJoined,
                'choice_label' => 'name',
                'label' => false,
                'data' => $tournamentSelected
            ))
            ->add('date_from', DateType::class, array(
                'input' => 'string',
                'format' => 'yyyy-MM-dd',
//                'widget' => 'single_text',
                'label' => false,
                'years' => range(date('Y') -10, date('Y') +10),
                'data' => $date_from
            ))
            ->add('date_to', DateType::class, array(
                'input' => 'string',
                'format' => 'yyyy-MM-dd',
//                'widget' => 'single_text',
                'label' => false,
                'years' => range(date('Y') -10, date('Y') +10),
                'data' => $date_to
            ))
            ->add('button', SubmitType::class, array('label' => 'Filter'))
            ->getForm();

        $filterForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $formData = $filterForm->getData();

            $userChoice = $formData['user'];
            $tournamentChoice = $formData['tournament_id'];
            $dateFromChoice = $formData['date_from'];
            $dateToChoice = $formData['date_to'];

            // reload the page with the chosen filter(s) applied (as url path params)
            return $this->redirectToRoute(
                'history_index',
                array(
                    'user_id' => $userChoice->getId(),
                    'tournament' => $tournamentChoice->getId(),
                    'date_from' => $dateFromChoice,
                    'date_to' => $dateToChoice
                )
            );
        }

        // get finished scored matches and the user's predictions for them
        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getAlreadyScored($user, $tournament, $date_from, $date_to);
        $predictions = $em->getRepository('DevlabsSportifyBundle:Prediction')
            ->getAlreadyScored($user, $tournament, $date_from, $date_to);

        // rendering the view and returning the response
        return $this->render(
            'DevlabsSportifyBundle:History:index.html.twig',
            array(
                'matches' => $matches,
                'predictions' => $predictions,
                'filter_form' => $filterForm->createView()
            )
        );
    }
}
