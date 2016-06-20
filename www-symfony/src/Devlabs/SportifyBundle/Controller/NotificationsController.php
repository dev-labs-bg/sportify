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

        $dateFrom = date("Y-m-d H:i:s");
        $dateTo = date("Y-m-d H:i:s", time() + 3600);

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getUpcoming($dateFrom, $dateTo);

        foreach ($matches as $match) {
            $users = $em->getRepository('DevlabsSportifyBundle:User')
                ->getNotPredictedByMatch($dateFrom, $dateTo);

        }

        $httpClient = new Client();
        $slackURL = 'https://hooks.slack.com/services/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';
        $slackBody = [
            'channel' => '@ceco',
            'text' => '<@ceco> пробвам малко API 123'
        ];

        $httpClient->post(
            $slackURL,
            [
                'body' => json_encode($slackBody),
                'allow_redirects' => false,
                'timeout'         => 3
            ]
        );

        // redirect to the Home page
        return $this->redirectToRoute('home');
    }
}
