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
     * @ORM\OneToMany(targetEntity="Score" , mappedBy="userId" , cascade={"all"})
     */
    private $scores;

    public function __construct() {
        parent::__construct();
        $this->scores = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
