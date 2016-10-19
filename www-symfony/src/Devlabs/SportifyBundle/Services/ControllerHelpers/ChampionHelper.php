<?php

namespace Devlabs\SportifyBundle\Services\ControllerHelpers;

use Devlabs\SportifyBundle\Entity\Tournament;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Devlabs\SportifyBundle\Entity\User;
use Devlabs\SportifyBundle\Entity\PredictionChampion;
use Symfony\Component\Form\Form;
use Devlabs\SportifyBundle\Form\ChampionSelectType;

/**
 * Class ChampionHelper
 * @package Devlabs\SportifyBundle\Services
 */
class ChampionHelper
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }

    /**
     * Return a PredictionChampion object
     *
     * @param User $user
     * @param Tournament $tournament
     * @return mixed
     */
    public function getPredictionChampion(User $user, Tournament $tournament)
    {
        // get user's champion prediction or null if there's none
        $predictionChampion = $this->em->getRepository('DevlabsSportifyBundle:PredictionChampion')
            ->getByUserAndTournament($user, $tournament);

        // get a new PredictionChampion object if none
        if ($predictionChampion === null) {
            $predictionChampion = new PredictionChampion();
        }

        return $predictionChampion;
    }

    /**
     * Get input data for the Champion team select form
     *
     * @param PredictionChampion $predictionChampion
     * @param Tournament $tournament
     * @return array
     */
    public function getFormInputData(PredictionChampion $predictionChampion, Tournament $tournament)
    {
        $formInputData = array();

        // determine if the user has already set a champion prediction
        if (!$predictionChampion->getId()) {
            $teamSelected = $this->em->getRepository('DevlabsSportifyBundle:Team')
                ->getFirstByTournament($tournament);
            $buttonAction = 'BET';
        } else {
            $teamSelected = $predictionChampion->getTeamId();
            $buttonAction = 'EDIT';
        }

        // get a list of teams for the selected tournament
        $teamChoices = $this->em->getRepository('DevlabsSportifyBundle:Team')
            ->getAllByTournament($tournament);

        // set the input form-data for the champion form
        $formInputData['team']['data'] = $teamSelected;
        $formInputData['team']['choices'] = $teamChoices;
        $formInputData['button_action'] = $buttonAction;

        return $formInputData;
    }

    /**
     * Create a Champion team select form
     *
     * @param array $formInputData
     * @return mixed
     */
    public function createForm(array $formInputData)
    {
        $formData = array();

        // creating the form for selecting the champion team
        $form = $this->container->get('form.factory')->create(ChampionSelectType::class, $formData, array(
            'data' => $formInputData
        ));

        return $form;
    }

    /**
     * Execute actions after a form is submitted
     *
     * @param Form $form
     * @param User $user
     * @param PredictionChampion $predictionChampion
     * @param Tournament $tournament
     */
    public function actionOnFormSubmit(Form $form, User $user, PredictionChampion $predictionChampion, Tournament $tournament)
    {
        // get the team selected via the form
        $formData = $form->getData();
        $teamChoice = $formData['team']->getId();

        // prepare the PredictionChampion object (new or modified one) for persisting in DB
        if ($formData['action'] === 'BET') {
            $predictionChampion = new PredictionChampion();
            $predictionChampion->setUserId($user);
            $predictionChampion->setTournamentId($tournament);
            $predictionChampion->setTeamId($teamChoice);
        } elseif ($formData['action'] === 'EDIT') {
            $predictionChampion->setTeamId($teamChoice);
        }

        // prepare the queries
        $this->em->persist($predictionChampion);

        // execute the queries
        $this->em->flush();
    }
}