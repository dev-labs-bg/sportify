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

        if ($updateType === 'fixtures-next7days') {
            // set dateFrom and dateTo to respectively today and 1 week on
            $dateFrom = date("Y-m-d");
            $dateTo = date("Y-m-d", time() + 604800);
        } else if ($updateType === 'fixtures-past1day') {
            // set dateFrom and dateTo to respectively yesterday and today
            $dateFrom = date("Y-m-d", time() - 86400);
            $dateTo = date("Y-m-d");
        }

        // return if dateFrom and dateTo have not been set
        if (!isset($dateFrom) || !isset($dateTo)) {
            return;
        }

        // get instance of the DataUpdates Manager service and initiate Fetch, Parse, Import services
        $status = $this->getContainer()->get('app.data_updates.manager')->updateFixtures($dateFrom, $dateTo);

        $logText = 'Command executed at: ' . date("Y-m-d H:i:s") . "\n";

        foreach ($status as $tournament) {
            $logText = $logText. "\n" . $tournament['name']. "\n" .
                'Fixtures fetched: ' . $tournament['status']['fixtures_fetched'] . "\n" .
                'Fixtures added: ' . $tournament['status']['fixtures_added'] . "\n" .
                'Fixtures updated: ' . $tournament['status']['fixtures_updated'] . "\n";
        }

        // get instance of the ScoreUpdater service and update all scores
        $this->getContainer()->get('app.score_updater')->updateAll();

        $output->writeln($logText);
    }
}
