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

        // continue only if user has Admin access, else redirect to Home
        if ($user->getEmail() !== 'ceco@devlabs.bg') {
            return $this->redirectToRoute('home');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        $tournament = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findOneById(12);

        $dataUpdatesManager = $this->get('app.data_updates.manager');
        $dataUpdatesManager->setEntityManager($em);

//        $dataUpdatesManager->updateTeamsByTournament($tournament);

        $formData = array();

        $form = $this->createFormBuilder($formData)
            ->add('task_type', ChoiceType::class, array(
                'choices'  => array(
                    'Matches update - Next 7 days' => 'fixtures-next7days',
                    'Matches update - Past 1 day' => 'fixtures-past1day',
                    'Scores update all' => 'scores-update-all'
                )))
            ->add('button', SubmitType::class, array('label' => 'Select'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Get instance of the Slack service and send notification
            $slack = $this->get('app.slack');
            $slack->setUrl('https://hooks.slack.com/services/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');
            $slack->setChannel('#sportify');

            if ($data['task_type'] === 'fixtures-next7days') {
                // set dateFrom and dateTo to respectively today and 1 week on
                $dateFrom = date("Y-m-d");
                $dateTo = date("Y-m-d", time() + 604800);

                $slackText = '<!channel>: Match fixtures updated for next 7 days.';

                $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);
            } else if ($data['task_type'] === 'fixtures-past1day') {
                // set dateFrom and dateTo to respectively yesterday and today
                $dateFrom = date("Y-m-d", time() - 86400);
                $dateTo = date("Y-m-d");

//                $slackText = '<!channel>: Match results updated for past 1 day.';

                $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);
            } else if ($data['task_type'] === 'scores-update-all') {
                // Get the ScoreUpdater service and update all scores
                $scoresUpdater = $this->get('app.score_updater');
                $scoresUpdater->setEntityManager($em);
                $scoresUpdater->updateAll();

                $slackText = '<!channel>: Match results and standings updated.';
            }

            // send slack notification
            $slack->setText($slackText);
            $slack->post();

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
