<?php

namespace Devlabs\SportifyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

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
            $httpClient = new Client();
            $slackURL = 'https://hooks.slack.com/services/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';
            $dateFrom = '2016-06-21 21:02:00';
//        $dateFrom = date("Y-m-d H:i:s");
            $dateTo = date("Y-m-d H:i:s", strtotime($dateFrom) + 3600);

            // Get an instance of the Entity Manager
            $em = $this->getContainer()->get('doctrine')->getManager();

            // get the upcoming matches for the next 1 hour
            $matches = $em->getRepository('DevlabsSportifyBundle:Match')
                ->getUpcoming($dateFrom, $dateTo);

            if ($matches) {
                $slackBody = [
                    'channel' => '@ceco',
                    'text' => 'Upcoming matches without prediction(s):'
                ];

                $httpClient->post(
                    $slackURL,
                    [
                        'body' => json_encode($slackBody),
                        'allow_redirects' => false,
                        'timeout'         => 5
                    ]
                );
            }

            $notifiedText = '';

            foreach ($matches as $match) {
                $notifiedText = $notifiedText . "\n";

                $matchText = $match->getDatetime()->format('Y-m-d H:i')." : ".$match->getHomeTeam()." - ".$match->getAwayTeam();

                $usersNotPredicted = $em->getRepository('DevlabsSportifyBundle:User')
                    ->getNotPredictedByMatch($match);

                $notifiedText = $notifiedText . "Match: " . $matchText . "\n";

                foreach ($usersNotPredicted as $user) {
                    if ($user->getSlackUsername() === 'ceco') {
                        $slackBody = [
                            'channel' => '@'.$user->getSlackUsername(),
                            'text' => $matchText
                        ];

                        $httpClient->post(
                            $slackURL,
                            [
                                'body' => json_encode($slackBody),
                                'allow_redirects' => false,
                                'timeout' => 5
                            ]
                        );
                    }

                    $notifiedText = $notifiedText.' '.$user->getSlackUsername();
                }

                $match->setNotificationSent('1');

                // prepare queries
                $em->persist($match);
            }

            // execute the queries
            $em->flush();

            $notifiedText = ($notifiedText === '') ? 'none' : $notifiedText;
        }

        $output->writeln("Notification(s) sent: \n".$notifiedText);
    }
}
