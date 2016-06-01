<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\MatchRepository")
 * @ORM\Table(name="matches")
 */
class Match
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", length=100)
     */
    private $datetime;

    /**
     * @ORM\Column(type="string", length=50, name="home_team")
     */
    private $homeTeam;

    /**
     * @ORM\Column(type="string", length=50, name="away_team")
     */
    private $awayTeam;

    /**
     * @ORM\Column(type="integer", name="home_goals")
     */
    private $homeGoals;

    /**
     * @ORM\Column(type="integer", name="away_goals")
     */
    private $awayGoals;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private $tournamentId;
}
