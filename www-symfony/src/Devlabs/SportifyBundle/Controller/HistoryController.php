<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Devlabs\SportifyBundle\Form\FilterType;

/**
 * Class HistoryController
 * @package Devlabs\SportifyBundle\Controller
 */
class HistoryController extends Controller
{
    /**
     * @Route("/history/{user_id}/{tournament_id}/{date_from}/{date_to}",
     *     name="history_index",
     *     defaults={
     *      "user_id" = "empty",
     *      "tournament_id" = "empty",
     *      "date_from" = "empty",
     *      "date_to" = "empty"
     *     }
     * )
     */
    public function indexAction(Request $request, $user_id, $tournament_id, $date_from, $date_to)
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Load the data for the current user into an object
        $user = $this->getUser();

        // set default values to route parameters if they are 'empty'
        if ($user_id === 'empty') $user_id = $user->getId();
        if ($tournament_id === 'empty') $tournament_id = 'all';
        if ($date_from === 'empty') $date_from = date("Y-m-d", time() - 1209600);
        if ($date_to === 'empty') $date_to = date("Y-m-d");

        $modifiedDateTo = date("Y-m-d", strtotime($date_to) + 86500);

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // use the selected user as object, based on id URL: {user_id} route parameter
        $userSelected = $em->getRepository('DevlabsSportifyBundle:User')
            ->findOneById($user_id);

        // get list of enabled users
        $usersEnabled = $em->getRepository('DevlabsSportifyBundle:User')
            ->getAllEnabled();

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
        $formInputData['user']['data'] = ($request->request->get('filter')) ? null : $userSelected;
        $formInputData['user']['choices'] = $usersEnabled;
        $formInputData['tournament']['data'] = ($request->request->get('filter')) ? null : $tournamentSelected;
        $formInputData['tournament']['choices'] = $tournamentsJoined;

        // creating a form for user,tournament,date filter
        $formData = array();
        $filterForm = $this->createForm(FilterType::class, $formData, array(
            'fields'=> array('tournament', 'user', 'date_from', 'date_to'),
            'data' => $formInputData
        ));

        $filterForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $formData = $filterForm->getData();

            $userChoice = $formData['user']->getId()->getId();
            $tournamentChoice = $formData['tournament']->getId()->getId();
            $dateFromChoice = $formData['date_from'];
            $dateToChoice = $formData['date_to'];

            // reload the page with the chosen filter(s) applied (as url path params)
            return $this->redirectToRoute(
                'history_index',
                array(
                    'user_id' => $userChoice,
                    'tournament_id' => $tournamentChoice,
                    'date_from' => $dateFromChoice,
                    'date_to' => $dateToChoice
                )
            );
        }

        // get finished scored matches and the user's predictions for them
        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getAlreadyScored($userSelected, $tournament_id, $date_from, $modifiedDateTo);
        $predictions = $em->getRepository('DevlabsSportifyBundle:Prediction')
            ->getAlreadyScored($userSelected, $tournament_id, $date_from, $modifiedDateTo);

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $twig = $this->container->get('twig');
        $twig->addGlobal('user_scores', $userScores);

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
