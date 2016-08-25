<?php

namespace Devlabs\SportifyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DataUpdateCommand
 * @package Devlabs\SportifyBundle\Command
 */
class DataUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sportify:data:update')
            ->setDescription('Notify users via Slack')
            ->addArgument(
                'data-update-type',
                InputArgument::OPTIONAL,
                'What data do you want to update?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updateType = $input->getArgument('data-update-type');

        if ($updateType === 'matches-next7days') {
            // set dateFrom and dateTo to respectively today and 1 week on
            $dateFrom = date("Y-m-d");
            $dateTo = date("Y-m-d", time() + (3600 * 24 * 7));
        } else if ($updateType === 'matches-past1day') {
            // set dateFrom and dateTo to respectively yesterday and today
            $dateFrom = date("Y-m-d", time() - (3600 * 24 * 1));
            $dateTo = date("Y-m-d");
        }

        // return if dateFrom and dateTo have not been set
        if (!isset($dateFrom) || !isset($dateTo)) {
            return;
        }

        $slackNotify = false;

        // get instance of the DataUpdates Manager service and initiate Fetch, Parse, Import services
        $status = $this->getContainer()->get('app.data_updates.manager')->updateFixtures($dateFrom, $dateTo);

        $logText = 'Command executed at: ' . date("Y-m-d H:i:s") . "\n";

        foreach ($status['tournaments'] as $tournament) {
            $logText = $logText. "\n" . $tournament['name']. "\n" .
                'Fixtures fetched: ' . $tournament['status']['fixtures_fetched'] . "\n" .
                'Fixtures added: ' . $tournament['status']['fixtures_added'] . "\n" .
                'Fixtures updated: ' . $tournament['status']['fixtures_updated'] . "\n";
        }

        // set Slack message text if new fixtures were added
        if ($status['total_added'] > 0) {
            $slackNotify = true;
            $slackText = 'Match fixtures added for next 7 days. '
                .$status['total_added'].' fixtures added.';
        }

        // set Slack message text if fixtures were updated
        if ($status['total_updated'] > 0) {
            // Get the ScoreUpdater service and update all scores
            $tournamentsModified = $this->getContainer()->get('app.score_updater')->updateAll();

            $slackNotify = true;
            $slackText = 'Match results and standings updated for tournament(s):';

            foreach ($tournamentsModified as $tournament) {
                $slackText = $slackText . "\n" . $tournament->getName();
            }
        }

        // send Slack notification
        if ($slackNotify) {
            // Get instance of the Slack service and send notification
            $this->getContainer()->get('app.slack')->setText($slackText)->post();
        }

        $output->writeln($logText);
    }
}
