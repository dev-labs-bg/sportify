<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\PredictionRepository")
 * @ORM\Table(name="predictions")
 */
class Prediction
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Match")
     * @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     */
    private $matchId;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userId;

    /**
     * @ORM\Column(type="integer", name="home_goals")
     */
    private $homeGoals;

    /**
     * @ORM\Column(type="integer", name="away_goals")
     */
    private $awayGoals;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

    /**
     * @ORM\Column(type="boolean", name="score_added")
     */
    private $scoreAdded = 0;
}
