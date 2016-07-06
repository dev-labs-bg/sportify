<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Form\PredictionType;

/**
 * Class MatchesHelper
 * @package Devlabs\SportifyBundle\Services
 */
class MatchesHelper
{
    use ContainerAwareTrait;

    private $em;
    private $form;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function setEntityManager(ObjectManager $em)
    {
        $this->em = $em;

        return $this;
    }

    public function getMatches()
    {

    }

    public function preparePredictionData()
    {

    }

    public function createForm($request, $match, $predictions)
    {
        if (isset($predictions[$match->getId()])) {
            // link/merge prediction with EntityManager (set entity as managed by EM)
            $prediction = $this->em->merge($predictions[$match->getId()]);

            $buttonAction = 'EDIT';
        } else {
            $prediction = new Prediction();
            $prediction->setMatchId($match);
            $prediction->setUserId($user);

            $buttonAction = 'BET';
        }

        $this->form = $this->container->get('form.factory')->create(PredictionType::class, $prediction, array(
            'button_action' => $buttonAction
        ));

        $this->formHandleRequest($request, $match);

        return $this->form;
    }

    public function formHandleRequest($request, $match)
    {
        if ($request->request->get('prediction')['matchId'] == $match->getId()) {
            $this->form->handleRequest($request);
        }
    }

    public function actionOnFormSubmit()
    {
        $prediction = $this->form->getData();

        // prepare the queries
        $this->em->persist($prediction);

        // execute the queries
        $this->em->flush();
    }
}