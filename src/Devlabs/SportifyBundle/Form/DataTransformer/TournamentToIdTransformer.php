<?php

namespace Devlabs\SportifyBundle\Form\DataTransformer;

use Devlabs\SportifyBundle\Entity\Tournament;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TournamentToIdTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms an object (tournament) to a string (id).
     *
     * @param Tournament|null $tournament
     * @return string
     */
    public function transform($tournament)
    {
        if (null === $tournament) {
            return '';
        }

        return $tournament->getId();
    }

    /**
     * Transforms a string (id) to an object (tournament).
     *
     * @param  string $tournamentId
     * @return Tournament|null
     * @throws TransformationFailedException if object (tournament) is not found.
     */
    public function reverseTransform($tournamentId)
    {
        // return if no tournament id
        if (!$tournamentId) {
            return;
        }

        $tournament = $this->manager
            ->getRepository('DevlabsSportifyBundle:Tournament')
            // query for the tournament with this id
            ->find($tournamentId)
        ;

        if (null === $tournament) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'A tournament with id "%s" does not exist!',
                $tournamentId
            ));
        }

        return $tournament;
    }
}
