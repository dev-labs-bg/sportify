<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Form\PredictionType;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Entity\User;
use Devlabs\SportifyBundle\Entity\Match;
use Devlabs\SportifyBundle\Entity\Prediction;
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
}