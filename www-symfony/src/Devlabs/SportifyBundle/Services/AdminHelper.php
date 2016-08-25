<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class AdminHelper
 * @package Devlabs\SportifyBundle\Services
 */
class AdminHelper
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }

    /**
     * Method for creating Data Updates select form
     *
     * @param array $formData
     * @param array $options
     * @return mixed
     */
    public function createDataUpdatesForm(array $formData = array(), array $options = array())
    {
        // creating for select Data Update type
        $form = $this->container->get('form.factory')->createBuilder(FormType::class, $formData, $options)
            ->add('update_type', ChoiceType::class, array(
                'choices'  => array(
                    'Matches (Next 7 days)' => 'matches-next7days',
                    'Matches (Past 1 day) and Scores Update' => 'matches-past1day-and-user-scores',
                    'Teams for all tournaments' => 'teams-all-tournaments'
                )))
            ->add('button', SubmitType::class, array('label' => 'Select'))
            ->getForm();

        return $form;
    }

    /**
     * Method for executing actions after the Data Updates select form is submitted
     *
     * @param Form $form
     */
    public function actionOnDataUpdatesFormSubmit(Form $form)
    {
        $data = $form->getData();

        $dataUpdatesManager = $this->container->get('app.data_updates.manager');
        $slackNotify = false;

        if ($data['update_type'] === 'matches-next7days') {
            // set dateFrom and dateTo to respectively today and 1 week on
            $dateFrom = date("Y-m-d");
            $dateTo = date("Y-m-d", time() + (3600 * 24 * 7));
            $status = $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);

            if ($status['total_added'] > 0) {
                $slackNotify = true;
                $slackText = 'Match fixtures added for next 7 days. '
                    .$status['total_added'].' fixtures added.';
            }
        } else if ($data['update_type'] === 'matches-past1day-and-user-scores') {
            // set dateFrom and dateTo to respectively yesterday and today
            $dateFrom = date("Y-m-d", time() - (3600 * 24 * 1));
            $dateTo = date("Y-m-d");
            $status = $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);

            if ($status['total_updated'] > 0) {
                // Get the ScoreUpdater service and update all scores
                $tournamentsModified = $this->container->get('app.score_updater')->updateAll();

                $slackNotify = true;
                $slackText = 'Match results and standings updated for tournament(s):';

                foreach ($tournamentsModified as $tournament) {
                    $slackText = $slackText . "\n" . $tournament->getName();
                }
            }
        } else if ($data['update_type'] === 'teams-all-tournaments') {
            $tournaments = $this->em->getRepository('DevlabsSportifyBundle:Tournament')
                ->findAll();

            foreach ($tournaments as $tournament) {
                $dataUpdatesManager->updateTeamsByTournament($tournament);
            }
        }

        // send Slack notification
        if ($slackNotify) {
            // Get instance of the Slack service and send notification
            $this->container->get('app.slack')->setText($slackText)->post();
        }
    }
}