<?php

namespace Devlabs\SportifyBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Tournament", inversedBy="users")
     * @ORM\JoinTable(name="scores")
     */
    private $tournaments;

    public function __construct() {
        parent::__construct();
        $this->tournaments = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
