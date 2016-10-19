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
use Devlabs\SportifyBundle\Entity\Team;
use Devlabs\SportifyBundle\Entity\Match;
use Devlabs\SportifyBundle\Form\ApiMappingType;
use Devlabs\SportifyBundle\Form\TournamentEntityType;
use Devlabs\SportifyBundle\Form\TeamEntityType;
use Devlabs\SportifyBundle\Form\MatchEntityType;

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
        $slackText = '';

        if ($data['update_type'] === 'matches-fixtures') {
            // set dateFrom and dateTo to respectively today and 'number of days' on
            $dateFrom = date("Y-m-d");
            $dateTo = date("Y-m-d", time() + (3600 * 24 * $data['days']));
            $status = $dataUpdatesManager->updateFixtures($dateFrom, $dateTo);

            if ($status['total_added'] > 0) {
                $slackNotify = true;
                $slackText = 'Match fixtures added for next '.$data['days'].' days. '
                    .$status['total_added'].' fixture(s) added.';
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

        if ($slackText === '') $slackText = 'No fixtures/results added or updated.';

        $this->container->get('session')->getFlashBag()->add('message', $slackText);
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
     * Method for getting the value for the entity form's button action
     * 'CREATE' or 'EDIT', depending on if object exists or is new
     *
     * @param $object
     * @return string
     */
    public function getButtonAction($object)
    {
        return ($object->getId())
            ? 'EDIT'
            : 'CREATE';
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
     * Method for creating array of ApiMapping forms
     *
     * @param array $tournaments
     * @return array
     */
    public function createApiMappingForms(array $tournaments)
    {
        $forms = array();

        // creating a form for each Api Mapping
        foreach ($tournaments as $tournament) {
            // get the ApiMapping object and buttonAction
            $apiMapping = $this->getApiMapping(
                $tournament,
                $this->container->getParameter('football_api.name')
            );

            $buttonAction = $this->getButtonAction($apiMapping);

            // create form for ApiMapping form
            $form = $this->createApiMappingForm($apiMapping, $buttonAction);

            // create view for each tournament's form
            $forms[$tournament->getId()] = $form->createView();
        }

        return $forms;
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

        /**
         * If the object is Team and a file has been uploaded, set it as TeamLogo
         */
        if (get_class($object) === 'Devlabs\SportifyBundle\Entity\Team' && $object->getUploadFile()) {
            // get the uploaded file's path and extension (file type)
            $filePath = $object->getUploadFile()->getPathName();
            $fileExtension = $object->getUploadFile()->guessExtension();

            // write the file to disk as team logo
            $object->setTeamLogo($filePath, $fileExtension);
        }

        /**
         * If the object is Tournament and a file has been uploaded, set it as Logo
         */
        if (get_class($object) === 'Devlabs\SportifyBundle\Entity\Tournament' && $object->getUploadFile()) {
            // get the uploaded file's path and extension (file type)
            $filePath = $object->getUploadFile()->getPathName();
            $fileExtension = $object->getUploadFile()->guessExtension();

            // write the file to disk as tournament logo
            $object->setLogo($filePath, $fileExtension);
        }
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
     * Get input data for Tournament form
     *
     * @param Tournament $tournament
     * @return array
     */
    public function getTournamentFormInputData(Tournament $tournament = null)
    {
        $formInputData = array();

        if (get_class($tournament) !== 'Devlabs\SportifyBundle\Entity\Tournament') {
            $formInputData['team']['data'] = null;
            $formInputData['team']['choices'] = array();

            return $formInputData;
        }

        $championTeam = ($tournament->getChampionTeamId())
            ? $tournament->getChampionTeamId()
            : null;

        // get a list of teams for the selected tournament
        $teamChoices = $this->em->getRepository('DevlabsSportifyBundle:Team')
            ->getAllByTournament($tournament);

        // set the input form-data for the tournament form
        $formInputData['team']['data'] = $championTeam;
        $formInputData['team']['choices'] = $teamChoices;

        return $formInputData;
    }

    /**
     * Method for creating Tournament Entity form
     *
     * @param Tournament $tournament
     * @param $buttonAction
     * @return mixed
     */
    public function createTournamentForm(Tournament $tournament, $buttonAction, $formInputData = array())
    {
        $form = $this->container->get('form.factory')->create(TournamentEntityType::class, $tournament, array(
            'action' => $this->container->get('router')->generate('admin_tournaments_modify'),
            'button_action' => $buttonAction,
//            'other_data' => $formInputData
        ));

        return $form;
    }

    /**
     * Method for creating array of Tournament forms
     *
     * @param array $tournaments
     * @return array
     */
    public function createTournamentForms(array $tournaments)
    {
        $forms = array();

        // creating a form for each tournament
        foreach ($tournaments as $tournament) {
            // get buttonAction
            $buttonAction = $this->getButtonAction($tournament);
//            $formInputData = $this->getTournamentFormInputData($tournament);

            // create form
            $form = $this->createTournamentForm($tournament, $buttonAction);

            // create view for each tournament's form
            $forms[$tournament->getId()] = $form->createView();
        }

        return $forms;
    }

    /**
     * Method for creating Team Entity form
     *
     * @param Team $team
     * @param $buttonAction
     * @return mixed
     */
    public function createTeamForm(Team $team, $buttonAction)
    {
        $form = $this->container->get('form.factory')->create(TeamEntityType::class, $team, array(
            'action' => $this->container->get('router')->generate('admin_teams_modify'),
            'button_action' => $buttonAction
        ));

        return $form;
    }

    /**
     * Method for creating array of Team forms
     *
     * @param array $teams
     * @return array
     */
    public function createTeamForms(array $teams)
    {
        $forms = array();

        // creating a form for each team
        foreach ($teams as $team) {
            // get buttonAction
            $buttonAction = $this->getButtonAction($team);

            // create form
            $form = $this->createTeamForm($team, $buttonAction);

            // create view for each form
            $forms[$team->getId()] = $form->createView();
        }

        return $forms;
    }

    /**
     * Get the input data for the Match form
     *
     * @param Tournament $tournament
     * @return array
     */
    public function getMatchFormInputData(Match $match)
    {
        $tournament = $match->getTournamentId();
        $formInputData = array();

        $homeTeam = ($match->getHomeTeamId())
            ? $match->getHomeTeamId()
            : $this->em->getRepository('DevlabsSportifyBundle:Team')->getFirstByTournament($tournament);
        $awayTeam = ($match->getAwayTeamId())
            ? $match->getAwayTeamId()
            : $this->em->getRepository('DevlabsSportifyBundle:Team')->getFirstByTournament($tournament);

        // get a list of teams for the selected tournament
        $teamChoices = $this->em->getRepository('DevlabsSportifyBundle:Team')
            ->getAllByTournament($tournament);

        // set the input form-data for the match form
        $formInputData['team']['home'] = $homeTeam;
        $formInputData['team']['away'] = $awayTeam;
        $formInputData['team']['choices'] = $teamChoices;

        return $formInputData;
    }

    /**
     * Method for creating Match Entity form
     *
     * @param Match $match
     * @param $buttonAction
     * @return mixed
     */
    public function createMatchForm(array $urlParams, Match $match, $buttonAction, $formInputData)
    {
        $form = $this->container->get('form.factory')->create(MatchEntityType::class, $match, array(
            'action' => $this->container->get('router')->generate('admin_matches_modify', $urlParams),
            'button_action' => $buttonAction,
            'other_data' => $formInputData
        ));

        return $form;
    }

    /**
     * Method for creating array of Match forms
     *
     * @param array $matches
     * @return array
     */
    public function createMatchForms(array $urlParams, array $matches)
    {
        $forms = array();

        // creating a form for each team
        foreach ($matches as $match) {
            // get buttonAction
            $buttonAction = $this->getButtonAction($match);
            $formInputData = $this->getMatchFormInputData($match);

            // create form
            $form = $this->createMatchForm($urlParams, $match, $buttonAction, $formInputData);

            // create view for each form
            $forms[$match->getId()] = $form->createView();
        }

        return $forms;
    }
}