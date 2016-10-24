<?php

namespace Devlabs\SportifyBundle\Services\ControllerHelpers;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Devlabs\SportifyBundle\Form\PredictionType;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Entity\User;
use Devlabs\SportifyBundle\Entity\Match;
use Devlabs\SportifyBundle\Entity\Prediction;
use Symfony\Component\Form\Form;

/**
 * Class MatchesHelper
 * @package Devlabs\SportifyBundle\Services
 */
class MatchesHelper
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }

    /**
     * Initialize URL parameters,
     * based on pre-defined rules for default values, etc.
     *
     * @param $tournament_id
     * @param $date_from
     * @param $date_to
     * @return array
     */
    public function initUrlParams($tournament_id, $date_from, $date_to)
    {
        if ($tournament_id === 'empty') $tournament_id = 'all';
        if ($date_from === 'empty') $date_from = date("Y-m-d");
        if ($date_to === 'empty') $date_to = date("Y-m-d", time() + 1209600);

        return array(
            'tournament_id' => $tournament_id,
            'date_from' => $date_from,
            'date_to' => $date_to
        );
    }

    /**
     * Get a Prediction object, which will be used in a prediction form
     *
     * @param $user
     * @param $match
     * @param $predictions
     * @return Prediction
     */
    public function getPrediction(User $user, Match $match, array $predictions)
    {
        if (isset($predictions[$match->getId()])) {
            // link/merge prediction with EntityManager (set entity as managed by EM)
            $prediction = $this->em->merge($predictions[$match->getId()]);
        } else {
            $prediction = new Prediction();
            $prediction->setMatchId($match);
            $prediction->setUserId($user);
        }

        return $prediction;
    }

    /**
     * Get the value for the prediction form's button
     *
     * @param $prediction
     * @return string
     */
    public function getPredictionButton(Prediction $prediction)
    {
        return ($prediction->getId())
            ? 'EDIT'
            : 'BET';
    }

    /**
     * Create a Prediction form
     *
     * @param $request
     * @param $urlParams
     * @param $match
     * @param $prediction
     * @param $buttonAction
     * @return mixed
     */
    public function createForm(Request $request, array $urlParams, Match $match, Prediction $prediction, $buttonAction)
    {
        $form = $this->container->get('form.factory')->create(PredictionType::class, $prediction, array(
            'action' => $this->container->get('router')->generate('matches_bet', $urlParams),
            'button_action' => $buttonAction
        ));

        $this->formHandleRequest($request, $form, $match);

        return $form;
    }

    /**
     * Handle a form if the POST request matches the match ID
     *
     * @param $request
     * @param $form
     * @param $match
     */
    public function formHandleRequest(Request $request, Form $form, Match $match)
    {
        if ($request->request->get('prediction')['matchId'] == $match->getId()) {
            $form->handleRequest($request);
        }
    }

    /**
     * Execute actions after a form is submitted
     *
     * @param $form
     */
    public function actionOnFormSubmit(Form $form)
    {
        $prediction = $form->getData();

        // prepare the queries
        $this->em->persist($prediction);

        // execute the queries
        $this->em->flush();
    }
}
