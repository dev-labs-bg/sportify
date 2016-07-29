<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Form\TournamentType;
use Devlabs\SportifyBundle\Entity\Tournament;
use Devlabs\SportifyBundle\Entity\User;
use Devlabs\SportifyBundle\Entity\Score;
use Symfony\Component\Form\Form;

/**
 * Class TournamentsHelper
 * @package Devlabs\SportifyBundle\Services
 */
class TournamentsHelper
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Method for setting EntityManager
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
     * Method for getting the value (JOIN/LEAVE) for the tournament form's button
     *
     * @param $prediction
     * @return string
     */
    public function getButtonAction(Tournament $tournament, array $tournamentsJoined)
    {
        return in_array($tournament, $tournamentsJoined)
            ? 'LEAVE'
            : 'JOIN';
    }

    /**
     * Method for creating a Tournament form
     *
     * @param $formInputData
     * @return mixed
     */
    public function createForm(array $formInputData)
    {
        $formData = array();

        $form = $this->container->get('form.factory')->create(TournamentType::class, $formData, array(
            'data' => $formInputData
        ));

        return $form;
    }

    /**
     * Method for executing actions after form is submitted
     *
     * @param Form $form
     * @param User $user
     */
    public function actionOnFormSubmit(Form $form, User $user)
    {
        $formData = $form->getData();
        $tournament = $this->em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findOneById($formData['id']);

        // prepare the queries for tournament join/leave (add/delete row in `scores` table)
        if ($formData['action'] === 'JOIN') {
            $this->joinTournament($user, $tournament);
        } elseif ($formData['action'] === 'LEAVE') {
            $this->leaveTournament($user, $tournament);
        }
    }

    /**
     * Method for user to join a tournament
     *
     * @param User $user
     * @param Tournament $tournament
     */
    public function joinTournament(User $user, Tournament $tournament)
    {
        $score = new Score();
        $score->setUserId($user);
        $score->setTournamentId($tournament);

        $this->em->persist($score);

        // execute the queries
        $this->em->flush();
    }

    /**
     * Method for user to leave a tournament
     *
     * @param $user
     * @param $tournament
     */
    public function leaveTournament(User $user, Tournament $tournament)
    {
        $score = $this->em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUserAndTournament($user, $tournament);
        $this->em->remove($score);

        // execute the queries
        $this->em->flush();
    }
}