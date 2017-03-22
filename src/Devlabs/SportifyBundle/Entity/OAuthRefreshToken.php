<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;

/**
 * @ORM\Entity()
 * @ORM\Table(name="oauth_refresh_tokens")
 */
class OAuthRefreshToken extends BaseRefreshToken
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="OAuthClient")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $client;
}
