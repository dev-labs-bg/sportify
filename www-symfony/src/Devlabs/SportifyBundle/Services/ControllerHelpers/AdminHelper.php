<?php

namespace Devlabs\SportifyBundle\Services\ControllerHelpers;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
    public function createDataUpdatesForm($updateType, array $choices = array(), array $options = array())
    {
        $formData = array();

        // creating for select Data Update type
        $form = $this->container->get('form.factory')->createBuilder(FormType::class, $formData, $options)
            ->add('update_type', HiddenType::class, array(
                'label' => false,
                'data' => $updateType
            ));

        if ($choices) {
            $form->add('days', ChoiceType::class, array('choices' => $choices));
        }

        $form->add('button', SubmitType::class, array('label' => 'Update'));

        return $form->getForm();
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

        if ($data['update_type'] === 'matches-fixtures') {
            // set dateFrom and dateTo to respectively today and 'number of days' on
            $dateFrom = date("Y-m-d");
            $dateTo = date("Y-m-d", time() + (3600 * 24 * $data['days']));
            $status = $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);

            if ($status['total_added'] > 0) {
                $slackNotify = true;
                $slackText = 'Match fixtures added for next '.$data['days'].' days. '
                    .$status['total_added'].' fixtures added.';
            }
        } else if ($data['update_type'] === 'matches-results') {
            // set dateFrom and dateTo to respectively 'number of days' before and today
            $dateFrom = date("Y-m-d", time() - (3600 * 24 * $data['days']));
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
            $apiMapping->setEntityId($tournament->getId());
            $apiMapping->setEntityType('Tournament');
            $apiMapping->setApiName($footballApi);
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
            'action' => $this->container->get('router')->generate('admin_api_mappings_modify'),
            'button_action' => $buttonAction
        ));

        return $form;
    }

    /**
     * Method for making a decision what action to execute after Entity form is submitted,
     * based on which button is clicked
     *
     * @param Form $form
     */
    public function actionOnEntityFormSubmit(Form $form)
    {
        if ($form->get('button1') && $form->get('button1')->isClicked()) {
            $this->actionOnEntityFormSubmitButton1($form);
        } elseif ($form->get('button2') && $form->get('button2')->isClicked()) {
            $this->actionOnEntityFormSubmitButton2($form);
        }
    }

    /**
     * Method for executing actions after Entity form is submitted via Button1 (CREATE or EDIT)
     *
     * @param Form $form
     */
    public function actionOnEntityFormSubmitButton1(Form $form)
    {
        $object = $form->getData();

        // prepare the queries
        $this->em->persist($object);

        // execute the queries
        $this->em->flush();
    }

    /**
     * Method for executing actions after Entity form is submitted via Button2 (DELETE)
     *
     * @param Form $form
     */
    public function actionOnEntityFormSubmitButton2(Form $form)
    {
        $object = $form->getData();

        // prepare the queries
        $this->em->remove($object);

        // execute the queries
        $this->em->flush();
    }

    /**
     * Method for getting the value for the prediction form's button
     *
     * @param $prediction
     * @return string
     */
    public function getTournamentButton(Tournament $tournament)
    {
        return ($tournament->getId())
            ? 'EDIT'
            : 'CREATE';
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
            'action' => $this->container->get('router')->generate('admin_tournaments_modify'),
            'button_action' => $buttonAction
        ));

        return $form;
    }
}