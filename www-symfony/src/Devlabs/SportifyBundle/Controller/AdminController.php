<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
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

        // continue only if user is part of Admin Users list, else redirect to Home
        if (!in_array($user->getEmail(), $this->container->getParameter('admin.users'))) {
            return $this->redirectToRoute('home');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        $tournament = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findOneById(12);

        $dataUpdatesManager = $this->get('app.data_updates.manager');

//        $dataUpdatesManager->updateTeamsByTournament($tournament);

        $formData = array();

        $form = $this->createFormBuilder($formData)
            ->add('task_type', ChoiceType::class, array(
                'choices'  => array(
                    'Matches update (Next 7 days)' => 'fixtures-next7days',
                    'Matches update (Past 1 day) and Scores Update' => 'fixtures-past1day-and-score-update'
                )))
            ->add('button', SubmitType::class, array('label' => 'Select'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $slackNotify = false;

            if ($data['task_type'] === 'fixtures-next7days') {
                // set dateFrom and dateTo to respectively today and 1 week on
                $dateFrom = date("Y-m-d");
                $dateTo = date("Y-m-d", time() + 604800);
                $status = $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);

                if ($status['total_added'] > 0) {
                    $slackNotify = true;
                    $slackText = '<!channel>: Match fixtures added for next 7 days.';
                }
            } else if ($data['task_type'] === 'fixtures-past1day-and-score-update') {
                // set dateFrom and dateTo to respectively yesterday and today
                $dateFrom = date("Y-m-d", time() - 86400);
                $dateTo = date("Y-m-d");
                $status = $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);

                if ($status['total_updated'] > 0) {
                    // Get the ScoreUpdater service and update all scores
                    $scoresUpdater = $this->get('app.score_updater');
                    $scoresUpdater->updateAll();

                    $slackNotify = true;
                    $slackText = '<!channel>: Match results and standings updated.';
                }
            }

            if ($slackNotify) {
                // Get instance of the Slack service and send notification
                $slack = $this->get('app.slack');
                $slack->setUrl('https://hooks.slack.com/services/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');
                $slack->setChannel('#sportify');
                $slack->setText($slackText);
                $slack->post();
            }

            return $this->redirectToRoute('admin_index');
        }

        $form = $form->createView();

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'Admin/index.html.twig',
            array(
                'form' => $form
            )
        );
    }
}
