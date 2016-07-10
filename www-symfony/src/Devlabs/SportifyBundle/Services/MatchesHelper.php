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

    public function setEntityManager(ObjectManager $em)
    {
        $this->em = $em;

        return $this;
    }

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

    public function getPrediction($user, $match, $predictions)
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

    public function getPredictionButton($prediction)
    {
        return ($prediction->getId())
            ? 'EDIT'
            : 'BET';
    }

    public function createForm($request, $urlParams, $match, $prediction, $buttonAction)
    {
        $form = $this->container->get('form.factory')->create(PredictionType::class, $prediction, array(
            'action' => $this->container->get('router')->generate('matches_bet', $urlParams),
            'button_action' => $buttonAction
        ));

        $this->formHandleRequest($request, $form, $match);

        return $form;
    }

    public function formHandleRequest($request, $form, $match)
    {
        if ($request->request->get('prediction')['matchId'] == $match->getId()) {
            $form->handleRequest($request);
        }
    }

    public function actionOnFormSubmit($form)
    {
        $prediction = $form->getData();

        // prepare the queries
        $this->em->persist($prediction);

        // execute the queries
        $this->em->flush();
    }
}