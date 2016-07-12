<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GuzzleHttp\Client;

/**
 * Class NotificationsController
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
        $dateTo = date("Y-m-d H:i:s", strtotime($dateFrom) + 3600);

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();
        $matches = $em->getRepository('DevlabsSportifyBundle:Match')
            ->getUpcoming($dateFrom, $dateTo);

        if ($matches) {
            // creating a Slack object for setting and sending messages
            $slack = $this->getContainer()->get('app.slack');
            $slack->setUrl('https://hooks.slack.com/services/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');
            $slack->setChannel('@ceco');
            $slack->setText('Потребители, които все още не са дали прогноза за предстоящите мачове:');
            $slack->post();
        }

        foreach ($matches as $match) {
            $matchText = $match->getDatetime()->format('Y-m-d H:i')." : ".$match->getHomeTeam()." - ".$match->getAwayTeam();

            $usersNotPredicted = $em->getRepository('DevlabsSportifyBundle:User')
                ->getNotPredictedByMatch($match);

            $userList = '';
            foreach ($usersNotPredicted as $user) {
                $userList = $userList.' <@'.$user->getSlackUsername().'>';
            }

            $matchText = $matchText." : ".$userList;

            $slack->setChannel('@ceco');
            $slack->setText($matchText);
            $slack->post();
        }

        // redirect to the Home page
        return $this->redirectToRoute('home');
    }
}
