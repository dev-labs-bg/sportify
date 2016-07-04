<?php

namespace Devlabs\SportifyBundle\Form\DataTransformer;

use Devlabs\SportifyBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserToIdTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms an object (user) to a string (id).
     *
     * @param  User|null $user
     * @return string
     */
    public function transform($user)
    {
        if (null === $user) {
            return '';
        }

        return $user->getId();
    }

    /**
     * Transforms a string (id) to an object (user).
     *
     * @param  string $userId
     * @return User|null
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($userId)
    {
        // no user id? It's optional, so that's ok
        if (!$userId) {
            return;
        }

        $user = $this->manager
            ->getRepository('DevlabsSportifyBundle:User')
            // query for the user with this id
            ->find($userId)
        ;

        if (null === $user) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An user with id "%s" does not exist!',
                $userId
            ));
        }

        return $user;
    }
}
