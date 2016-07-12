<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Entity\User;

/**
 * Class HistoryHelper
 * @package Devlabs\SportifyBundle\Services
 */
class HistoryHelper
{
    use ContainerAwareTrait;

    private $em;
    private $currentUser;

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
     * Method for setting the current user
     *
     * @param User $user
     * @return $this
     */
    public function setCurrentUser(User $user)
    {
        $this->currentUser = $user;

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
    public function initUrlParams($user_id, $tournament_id, $date_from, $date_to)
    {
        if ($user_id === 'empty') $user_id = $this->currentUser->getId();
        if ($tournament_id === 'empty') $tournament_id = 'all';
        if ($date_from === 'empty') $date_from = date("Y-m-d", time() - 1209600);
        if ($date_to === 'empty') $date_to = date("Y-m-d");

        return array(
            'user_id' => $user_id,
            'tournament_id' => $tournament_id,
            'date_from' => $date_from,
            'date_to' => $date_to
        );
    }
}