<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Devlabs\SportifyBundle\Form\TournamentType;

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
     * Method for creating a Tournament form
     *
     * @param $formInputData
     * @return mixed
     */
    public function createForm($formInputData)
    {
        $formData = array();

        $form = $this->container->get('form.factory')->create(TournamentType::class, $formData, array(
            'data' => $formInputData
        ));

        return $form;
    }
}