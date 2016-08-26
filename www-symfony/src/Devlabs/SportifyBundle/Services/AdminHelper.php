<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Devlabs\SportifyBundle\Entity\ApiMapping;
use Devlabs\SportifyBundle\Entity\Tournament;
use Devlabs\SportifyBundle\Form\ApiMappingType;
use Devlabs\SportifyBundle\Form\TournamentEntityType;

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

    /**
     * Method for getting existin ApiMapping object from DB by Tournament and Football API name,
     * or returning a new ApiMapping object if none exists
     *
     * @param Tournament $tournament
     * @param $footballApi
     * @return ApiMapping
     */
    public function getApiMapping(Tournament $tournament, $footballApi)
    {
        // get existing ApiMapping or create new if none exists
        $apiMapping = $this->em->getRepository('DevlabsSportifyBundle:ApiMapping')
            ->getByEntityAndApiProvider($tournament, 'Tournament', $footballApi);

        // get a new ApiMapping object if none
        if ($apiMapping === null) {
            $apiMapping = new ApiMapping();
        }

        return $apiMapping;
    }

    /**
     * Method for determining the ApiMapping form button's action
     * 'CREATE' or 'EDIT', depending on where ApiMapping exists already
     *
     * @param ApiMapping $apiMapping
     * @return string
     */
    public function getApiMappingButtonAction(ApiMapping $apiMapping)
    {
        // determine if the ApiMapping already exists
        if (!$apiMapping->getId()) {
            $buttonAction = 'CREATE';
        } else {
            $buttonAction = 'EDIT';
        }

        return $buttonAction;
    }

    /**
     * Method for creating ApiMapping form
     *
     * @param ApiMapping $apiMapping
     * @param $buttonAction
     * @return mixed
     */
    public function createApiMappingForm(ApiMapping $apiMapping, $buttonAction)
    {
        $form = $this->container->get('form.factory')->create(ApiMappingType::class, $apiMapping, array(
            'button_action' => $buttonAction
        ));

        return $form;
    }

    /**
     * Method for executing actions after ApiMapping form is submitted
     *
     * @param $form
     */
    public function actionOnApiMappingFormSubmit(Form $form)
    {
        $apiMapping = $form->getData();

        // prepare the queries
        $this->em->persist($apiMapping);

        // execute the queries
        $this->em->flush();
    }

    /**
     * Method for creating Tournament Entity form
     *
     * @param Tournament $tournament
     * @param $buttonAction
     * @return mixed
     */
    public function createTournamentForm(Tournament $tournament, $buttonAction)
    {
        $form = $this->container->get('form.factory')->create(TournamentEntityType::class, $tournament, array(
            'button_action' => $buttonAction
        ));

        return $form;
    }

    /**
     * Method for executing actions after Tournament Entity form is submitted
     *
     * @param Form $form
     */
    public function actionOnTournamentFormSubmit(Form $form)
    {
        $tournament = $form->getData();

        // prepare the queries
        $this->em->persist($tournament);

        // execute the queries
        $this->em->flush();
    }
}