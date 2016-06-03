<?php

namespace Devlabs\SportifyBundle\Controller;

use Devlabs\SportifyBundle\Entity\User;
use Devlabs\SportifyBundle\Entity\Tournament;
use Devlabs\SportifyBundle\Entity\Score;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TournamentsController extends Controller
{
    /**
     * @Route("/tournaments", name="tournaments_index")
     */
    public function indexAction(Request $request)
    {
        // Load the data for the current user into an object
        $user = $this->getUser();

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get all
        $tournaments = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();
        $tournamentsJoined = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->getJoined($user);

        $forms = array();

        if ($tournaments) {
            foreach ($tournaments as $tournament) {
                $formData = array();
                $buttonAction = in_array($tournament, $tournamentsJoined)
                    ? 'leave'
                    : 'join';

                $form = $this->createFormBuilder($formData)
                    ->add('id', HiddenType::class, array('data' => $tournament->getId()))
                    ->add('action', HiddenType::class, array('data' => $buttonAction))
                    ->add('button', SubmitType::class, array('label' => $buttonAction))
                    ->getForm();

                $form->handleRequest($request);
                $forms[$tournament->getId()] = $form;
            }

            foreach ($forms as $form) {
                if ($form->isSubmitted() && $form->isValid()) {
                    $formData = $form->getData();
                    $tournament = $em->getRepository('DevlabsSportifyBundle:Tournament')
                        ->findOneById($formData['id']);

                    if ($formData['action'] === 'join') {
                        $score = new Score();
                        $score->setUserId($user);
                        $score->setTournamentId($tournament);

                        $em->persist($score);
                    } elseif ($formData['action'] === 'leave') {
                        $score = $em->getRepository('DevlabsSportifyBundle:Score')
                            ->getByUserAndTournament($user, $tournament);
                        $em->remove($score);
                    }

                    // execute the queries
                    $em->flush();

                    // clear the submitted data and reload the page
                    return $this->redirectToRoute('tournaments_index');
                }
            }

            foreach ($tournaments as $tournament) {
                $forms[$tournament->getId()] = $forms[$tournament->getId()]->createView();
            }
        }

        return $this->render(
            'DevlabsSportifyBundle:Tournaments:index.html.twig',
            array(
                'tournaments' => $tournaments,
                'forms' => $forms
            )
        );
    }
}
