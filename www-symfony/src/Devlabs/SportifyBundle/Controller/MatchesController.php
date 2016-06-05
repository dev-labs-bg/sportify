<?php

namespace Devlabs\SportifyBundle\Controller;

use Devlabs\SportifyBundle\Entity\Score;
use Devlabs\SportifyBundle\Entity\Prediction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class MatchesController
 * @package Devlabs\SportifyBundle\Controller
 */
class MatchesController extends Controller
{
    /**
     * @Route("/matches", name="matches_index")
     */
    public function indexAction(Request $request)
    {
        // Load the data for the current user into an object
        $user = $this->getUser();

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get not finished matches and the user's predictions for them
        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getNotScored($user);
        $predictions = $em->getRepository('DevlabsSportifyBundle:Prediction')
            ->getNotScored($user);

        $forms = array();

        if ($matches) {
            foreach ($matches as $match) {
                $prediction = new Prediction();
                $prediction->setHomeGoals('');
                $prediction->setAwayGoals('');
                $buttonAction = 'bet';

                if (isset($predictions[$match->getId()])) {
                    $prediction = $predictions[$match->getId()];
                    $buttonAction = 'edit';
                }

                $formData = array();
                $form = $this->createFormBuilder($formData)
                    ->add('match_id', HiddenType::class, array('data' => $match->getId()))
                    ->add('home_goals', TextType::class, array('data' => $prediction->getHomeGoals()))
                    ->add('away_goals', TextType::class, array('data' => $prediction->getAwayGoals()))
                    ->add('action', HiddenType::class, array('data' => $buttonAction))
                    ->add('button', SubmitType::class, array('label' => $buttonAction))
                    ->getForm();

                $form->handleRequest($request);
                $forms[$match->getId()] = $form;
            }

            // iterate the forms and and if form is submitted, then execute the join/leave tournament code
            foreach ($forms as $form) {
                if ($form->isSubmitted() && $form->isValid()) {
                    $formData = $form->getData();
                    $match = $em->getRepository('DevlabsSportifyBundle:Match')
                        ->findOneById($formData['match_id']);

                    // prepare the Prediction object (new or modified one) for persisting in DB
                    if ($formData['action'] === 'bet') {
                        $prediction = new Prediction();
                        $prediction->setUserId($user);
                        $prediction->setMatchId($match);
                        $prediction->setHomeGoals($formData['home_goals']);
                        $prediction->setAwayGoals($formData['away_goals']);
                    } elseif ($formData['action'] === 'edit') {
                        $prediction = $em->getRepository('DevlabsSportifyBundle:Prediction')
                            ->getOneByUserAndMatch($user, $match);
                        $prediction->setHomeGoals($formData['home_goals']);
                        $prediction->setAwayGoals($formData['away_goals']);
                    }

                    // prepare the queries
                    $em->persist($prediction);

                    // execute the queries
                    $em->flush();

                    // clear the submitted POST data and reload the page
                    return $this->redirectToRoute('matches_index');
                }
            }


            // create view for each matches' form
            foreach ($matches as $match) {
                $forms[$match->getId()] = $forms[$match->getId()]->createView();
            }
        }

        // rendering the view and returning the response
        return $this->render(
            'DevlabsSportifyBundle:Matches:index.html.twig',
            array(
                'matches' => $matches,
                'predictions' => $predictions,
                'forms' => $forms
            )
        );
    }
}
