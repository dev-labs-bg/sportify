<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GuzzleHttp\Client;

/**
 * Class ScoresUpdateController
 * @package Devlabs\SportifyBundle\Controller
 */
class NotificationsController extends Controller
{
    /**
     * @Route("/notify/notpredicted",
     *     name="notify_not_predicted"
     * )
     */
    public function notifyAction()
    {
        // if user is not logged in, redirect to login page
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $httpClient = new Client();
        $slackURL = 'https://hooks.slack.com/services/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';
//        $slackText = '<@ceco> пробвам малко API 123';
//        $slackBody = [
//            'channel' => '@ceco',
//            'text' => $slackText
//        ];

//        $dateFrom = date("Y-m-d H:i:s");
        $dateFrom = '2016-06-20 21:02:00';
        $dateTo = date("Y-m-d H:i:s", strtotime($dateFrom) + 98000);

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getUpcoming($dateFrom, $dateTo);

        foreach ($matches as $match) {
            $matchText = $match->getDatetime()->format('Y-m-d H:i:s')." : ".$match->getHomeTeam()." - ".$match->getAwayTeam();

            $usersNotPredicted = $em->getRepository('DevlabsSportifyBundle:User')
                ->getNotPredictedByMatch($match);

            foreach ($usersNotPredicted as $user) {
                $matchText = $matchText." : ".$user->getUsername();
            }

            $slackBody = [
                'channel' => '@ceco',
                'text' => $matchText
            ];

            $httpClient->post(
                $slackURL,
                [
                    'body' => json_encode($slackBody),
                    'allow_redirects' => false,
                    'timeout'         => 3
                ]
            );
        }

        // redirect to the Home page
        return $this->redirectToRoute('home');
    }
}
