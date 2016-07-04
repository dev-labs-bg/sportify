<?php

namespace Devlabs\SportifyBundle\Form\DataTransformer;

use Devlabs\SportifyBundle\Entity\Match;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MatchToIdTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms an object (match) to a string (id).
     *
     * @param  Match|null $match
     * @return string
     */
    public function transform($match)
    {
        if (null === $match) {
            return '';
        }

        return $match->getId();
    }

    /**
     * Transforms a string (id) to an object (match).
     *
     * @param  string $matchId
     * @return Match|null
     * @throws TransformationFailedException if object (match) is not found.
     */
    public function reverseTransform($matchId)
    {
        // no match id? It's optional, so that's ok
        if (!$matchId) {
            return;
        }

        $match = $this->manager
            ->getRepository('DevlabsSportifyBundle:Match')
            // query for the match with this id
            ->find($matchId)
        ;

        if (null === $match) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An match with id "%s" does not exist!',
                $matchId
            ));
        }

        return $match;
    }
}
