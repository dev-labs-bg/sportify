<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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

        // get the matches helper service
        $historyHelper = $this->container->get('app.history.helper');
        $historyHelper->setCurrentUser($user);

        // set default values to route parameters if they are 'empty'
        $urlParams = $historyHelper->initUrlParams($user_id, $tournament_id, $date_from, $date_to);

        $modifiedDateTo = date("Y-m-d", strtotime($urlParams['date_to']) + 86500);

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the filter helper service
        $filterHelper = $this->container->get('app.filter.helper');

        // set the fields for the filter form
        $fields = array('tournament', 'user', 'date_from', 'date_to');

        // set the input data for the filter form and create it
        $formSourceData = $filterHelper->getFormSourceData($user, $urlParams, $fields);
        $formInputData = $filterHelper->getFormInputData($request, $urlParams, $fields, $formSourceData);
        $filterForm = $filterHelper->createForm($fields, $formInputData);
        $filterForm->handleRequest($request);

        // if the filter form is submitted, redirect with appropriate url path parameters
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $submittedParams = $filterHelper->actionOnFormSubmit($filterForm, $fields);

            return $this->redirectToRoute('history_index', $submittedParams);
        }

        // get finished scored matches and the user's predictions for them
        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getAlreadyScored($formSourceData['user_selected'], $urlParams['tournament_id'], $urlParams['date_from'], $modifiedDateTo);
        $predictions = $em->getRepository('DevlabsSportifyBundle:Prediction')
            ->getAlreadyScored($formSourceData['user_selected'], $urlParams['tournament_id'], $urlParams['date_from'], $modifiedDateTo);

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'History/index.html.twig',
            array(
                'matches' => $matches,
                'predictions' => $predictions,
                'filter_form' => $filterForm->createView()
            )
        );
    }
}
