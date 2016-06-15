<?php

namespace Devlabs\SportifyBundle\Controller;

use Devlabs\SportifyBundle\Entity\Score;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class TournamentsController
 * @package Devlabs\SportifyBundle\Controller
 */
class TournamentsController extends Controller
{
    /**
     * @Route("/tournaments", name="tournaments_index")
     */
    public function indexAction(Request $request)
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

        // get all and joined tournaments lists
        $tournaments = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();
        $tournamentsJoined = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->getJoined($user);

        $forms = array();

        if ($tournaments) {
            // creating a form with JOIN/LEAVE button for each tournament
            foreach ($tournaments as $tournament) {
                // determine the button action, depending on if the tournament is joined
                $buttonAction = in_array($tournament, $tournamentsJoined)
                    ? 'LEAVE'
                    : 'JOIN';

                $formData = array();
                $form = $this->createFormBuilder($formData)
                    ->add('id', HiddenType::class, array('data' => $tournament->getId()))
                    ->add('action', HiddenType::class, array('data' => $buttonAction))
                    ->add('button', SubmitType::class, array('label' => $buttonAction))
                    ->getForm();

                $form->handleRequest($request);
                $forms[$tournament->getId()] = $form;
            }

            // iterate the forms and and if form is submitted, then execute the join/leave tournament code
            foreach ($forms as $form) {
                if ($form->isSubmitted() && $form->isValid()) {
                    $formData = $form->getData();
                    $tournament = $em->getRepository('DevlabsSportifyBundle:Tournament')
                        ->findOneById($formData['id']);

                    // prepare the queries for tournament join/leave (add/delete row in `scores` table)
                    if ($formData['action'] === 'JOIN') {
                        $score = new Score();
                        $score->setUserId($user);
                        $score->setTournamentId($tournament);
                        $em->persist($score);
                    } elseif ($formData['action'] === 'LEAVE') {
                        $score = $em->getRepository('DevlabsSportifyBundle:Score')
                            ->getByUserAndTournament($user, $tournament);
                        $em->remove($score);
                    }

                    // execute the queries
                    $em->flush();

                    // clear the submitted POST data and reload the page
                    return $this->redirectToRoute('tournaments_index');
                }
            }

            // create view for each tournament's form
            foreach ($tournaments as $tournament) {
                $forms[$tournament->getId()] = $forms[$tournament->getId()]->createView();
            }
        }

        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $twig = $this->container->get('twig');
        $twig->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'DevlabsSportifyBundle:Tournaments:index.html.twig',
            array(
                'tournaments' => $tournaments,
                'forms' => $forms
            )
        );
    }
}
