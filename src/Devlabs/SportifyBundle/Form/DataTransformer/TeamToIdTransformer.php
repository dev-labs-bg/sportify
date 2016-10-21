<?php

namespace Devlabs\SportifyBundle\Form\DataTransformer;

use Devlabs\SportifyBundle\Entity\Team;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TeamToIdTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms an object (team) to a string (id).
     *
     * @param  Team|null $team
     * @return string
     */
    public function transform($team)
    {
        if (null === $team) {
            return '';
        }

        return $team->getId();
    }

    /**
     * Transforms a string (id) to an object (team).
     *
     * @param  string $teamId
     * @return Team|null
     * @throws TransformationFailedException if object (team) is not found.
     */
    public function reverseTransform($teamId)
    {
        // return if no match id
        if (!$teamId) {
            return;
        }

        $team = $this->manager
            ->getRepository('DevlabsSportifyBundle:Team')
            // query for the team with this id
            ->find($teamId)
        ;

        if (null === $team) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'A team with id "%s" does not exist!',
                $teamId
            ));
        }

        return $team;
    }
}
