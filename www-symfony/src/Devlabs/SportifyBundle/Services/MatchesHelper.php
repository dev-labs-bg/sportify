<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Form\PredictionType;
use Devlabs\SportifyBundle\Entity\Prediction;

/**
 * Class MatchesHelper
 * @package Devlabs\SportifyBundle\Services
 */
class MatchesHelper
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Method for setting passing in an ObjectManager object
     * for retrieving data from the database
     *
     * @param ObjectManager $em
     * @return $this
     */
    public function setEntityManager(ObjectManager $em)
    {
        $this->em = $em;

        return $this;
    }

    /**
     * Method for initializing URL parameters,
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
     * Method for returning a Prediction object, which will be passed in to a prediction form
     *
     * @param $user
     * @param $match
     * @param $predictions
     * @return Prediction
     */
    public function getPrediction(\Devlabs\SportifyBundle\Entity\User $user, \Devlabs\SportifyBundle\Entity\Match $match, Array $predictions)
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
     * Method for getting the value for the prediction form's button
     *
     * @param $prediction
     * @return string
     */
    public function getPredictionButton(\Devlabs\SportifyBundle\Entity\Prediction $prediction)
    {
        return ($prediction->getId())
            ? 'EDIT'
            : 'BET';
    }

    /**
     * Method for creating a Prediction form
     *
     * @param $request
     * @param $urlParams
     * @param $match
     * @param $prediction
     * @param $buttonAction
     * @return mixed
     */
    public function createForm($request, $urlParams, $match, $prediction, $buttonAction)
    {
        $form = $this->container->get('form.factory')->create(PredictionType::class, $prediction, array(
            'action' => $this->container->get('router')->generate('matches_bet', $urlParams),
            'button_action' => $buttonAction
        ));

        $this->formHandleRequest($request, $form, $match);

        return $form;
    }

    /**
     * Method for handling a form if the POST request matches the match ID
     *
     * @param $request
     * @param $form
     * @param $match
     */
    public function formHandleRequest($request, $form, $match)
    {
        if ($request->request->get('prediction')['matchId'] == $match->getId()) {
            $form->handleRequest($request);
        }
    }

    /**
     * Method for executing actions after a form is submitted
     *
     * @param $form
     */
    public function actionOnFormSubmit($form)
    {
        $prediction = $form->getData();

        // prepare the queries
        $this->em->persist($prediction);

        // execute the queries
        $this->em->flush();
    }
}