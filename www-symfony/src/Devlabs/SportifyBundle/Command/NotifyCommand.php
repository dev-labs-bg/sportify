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

            $logText = 'Command executed at: ' . date("Y-m-d H:i:s") . "\n";

            if ($matches) {
                // creating a Slack object for setting and sending messages
                $slack = $this->getContainer()->get('app.slack');

                // array for holding the user's messages
                $messages = array();

                // set a Heading for all user messages
                $msgHeading = "Upcoming matches without prediction(s):";

                // iterate the matches and create the notification messages for each user
                foreach ($matches as $match) {
                    $matchText = $match->getDatetime()->format('Y-m-d H:i')
                        ." : ".$match->getHomeTeamId()->getName()." - ".$match->getAwayTeamId()->getName();
                    $logText = $logText . "\n" . "Match: " . $matchText . "\n";

                    // get the users which have no prediction for this match
                    $usersNotPredicted = $em->getRepository('DevlabsSportifyBundle:User')
                        ->getNotPredictedByMatch($match);

                    // creating the messages for each user
                    foreach ($usersNotPredicted as $user) {
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
                foreach ($usersNotPredicted as $user) {
                    $slack->setChannel('@'.$user->getSlackUsername())
                        ->setText($messages[$user->getId()])
                        ->post();
                }

                // execute queries
                $em->flush();
            }

            $logText = ($logText === '') ? 'none' : $logText;
            $output->writeln("Notification(s) sent: \n".$logText);
        }
    }
}
