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
            ->setDescription('Data updates via API fetch')
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'What data do you want to update?'
            )
            ->addArgument(
                'days',
                InputArgument::REQUIRED,
                'What period do you want to fetch data for? (days)'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updateType = $input->getArgument('type');
        $days = $input->getArgument('days');

        $dataUpdatesManager = $this->getContainer()->get('app.data_updates.manager');
        $dataUpdated = false;
        $msgText = '';
        $logText = 'Command for updating '.$updateType.' executed at: '.date("Y-m-d H:i:s");

        if ($updateType === 'matches-fixtures') {
            // set dateFrom and dateTo to respectively today and 'number of days' on
            $dateFrom = date("Y-m-d");
            $dateTo = date("Y-m-d", time() + (3600 * 24 * $days));
            $status = $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);

            if ($status['total_added'] > 0) {
                $dataUpdated = true;
                $msgText = 'Match fixtures added for next '.$days.' days. '
                    .$status['total_added'].' fixture(s) added.';
            }
        } elseif ($updateType === 'matches-results') {
            // set dateFrom and dateTo to respectively 'number of days' before and today
            $dateFrom = date("Y-m-d", time() - (3600 * 24 * $days));
            $dateTo = date("Y-m-d");
            $status = $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);

            if ($status['total_updated'] > 0) {
                // Get the ScoreUpdater service and update all scores
                $tournamentsModified = $this->getContainer()->get('app.score_updater')->updateAll();

                $dataUpdated = true;
                $msgText = 'Match results and standings updated for tournament(s):';

                foreach ($tournamentsModified as $tournament) {
                    $msgText = $msgText."\n".$tournament->getName();
                }
            }
        }

        if ($dataUpdated) {
            // Get instance of the Slack service and send notification
            $this->getContainer()->get('app.slack')->setText($msgText)->post();

            $logText = $logText . "\n" . $msgText . "\n";
        } else {
            $logText = $logText . "\n" . 'No fixtures/results added or updated.' . "\n";
        }

        $output->writeln($logText);
    }
}
