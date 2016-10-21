<?php

namespace Devlabs\SportifyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Devlabs\SportifyBundle\Entity\User;

/**
 * Class TwigHelper
 * @package Devlabs\SportifyBundle\Services
 */
class TwigHelper
{
    use ContainerAwareTrait;

    private $em;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }

    /**
     * Set a user's scores as global Twig variable
     *
     * @param User $user
     */
    public function setUserScores(User $user)
    {
        // get scores standings for user
        $userScores = $this->em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);

        // set user scores as Twig global var
        $this->container->get('twig')->addGlobal('user_scores', $userScores);
    }
}
