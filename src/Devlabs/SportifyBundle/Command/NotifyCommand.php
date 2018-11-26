<?php

namespace Devlabs\SportifyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class NotifyCommand
 * @package Devlabs\SportifyBundle\Command
 */
class NotifyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sportify:notify')
            ->setDescription('Notify users via Slack')
            ->addArgument(
                'reason',
                InputArgument::OPTIONAL,
                'Who do you want to notify?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $reason = $input->getArgument('reason');

        if ($reason === 'users-not-predicted') {
            $dateFrom = date("Y-m-d H:i:s");
            $dateTo = date("Y-m-d H:i:s", strtotime($dateFrom) + 7200);

            // Get an instance of the Entity Manager
            $em = $this->getContainer()->get('doctrine')->getManager();

            // get the upcoming matches for the next 1 hour
            $matches = $em->getRepository('DevlabsSportifyBundle:Match')
                ->getUpcoming($dateFrom, $dateTo);

            // array for holding the users' messages
            $messages = array();

            // array for holding the users' messages status if failed to send
            $failedMessages = array();

            $logTimestamp = date("Y-m-d H:i:s");
            $logText = '';

            if ($matches) {
                // creating a Slack object for setting and sending messages
                $slack = $this->getContainer()->get('app.slack');

                // set a Heading for all user messages
                $msgHeading = "Upcoming matches without prediction(s):";

                // iterate the matches and create the notification messages for each user
                foreach ($matches as $match) {
                    $matchText = $match->getDatetime()->format('Y-m-d H:i')
                        ." : ".$match->getHomeTeamId()->getName()." - ".$match->getAwayTeamId()->getName();
                    $logText = $logText . "\n" . "Match: " . $matchText . "\n";

                    // get the users which have no prediction for this match
                    $usersNotPredictedByMatch = $em->getRepository('DevlabsSportifyBundle:User')
                        ->getNotPredictedByMatch($match);

                    // creating the messages for each user
                    foreach ($usersNotPredictedByMatch as $user) {
                        if (!isset($messages[$user->getId()])) {
                            $messages[$user->getId()] = $msgHeading . "\n" . $matchText . "\n";
                        } else {
                            $messages[$user->getId()] = $messages[$user->getId()] . $matchText . "\n";
                        }

                        $logText = $logText.' '.$user->getSlackUsername();
                    }

                    // set the match's notification sent flag
                    $match->setNotificationSent('1');

                    // prepare queries
                    $em->persist($match);
                }

                /**
                 * Sending the messages to the users.
                 *
                 * This is a separate cycle because we want to send each user only 1 message,
                 * even if the notification is for more than 1 match
                 */
                foreach ($messages as $id => $message) {
                    // get the user for the message
                    $user = $em->getRepository('DevlabsSportifyBundle:User')
                        ->findOneById($id);

                    // send the notification
                    $slack->setChannel('@'.$user->getSlackUsername())
                        ->setText($message);
                    $response = $slack->post();

                    if ($response->getStatusCode() !== 200) {
                        $failedMessages[$user->getId()] = array(
                            'username' => $user->getUsername(),
                            'response_code' => $response->getStatusCode(),
                            'response_reason' => $response->getReasonPhrase()
                        );
                    }
                }
            }

            /**
             * Execute queries for setting the notifications sent flag
             * only if there is at least one successfully sent message.
             */
            if ($matches && $messages
                && count($messages) > count($failedMessages)) {
                $em->flush();
            }

            $logText = ($logText === '') ? 'none' : $logText;
            $outputText = $logTimestamp." --- Notification(s): ".$logText;

            if ($failedMessages) {
                $outputText = $outputText . "\nFailed notification(s):\n";

                foreach ($failedMessages as $failedMessage) {
                    $outputText = $outputText
                        ."username: ".$failedMessage['username']." | "
                        ."code: ".$failedMessage['response_code']." | "
                        ."reason: ".$failedMessage['response_reason']."\n";
                }
            }

            $output->writeln($outputText);
        }
    }
}
