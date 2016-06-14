<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\TournamentRepository")
 * @ORM\Table(name="tournaments")
 */
class Tournament
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", name="start_date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", name="end_date")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=10, name="name_short")
     */
    private $nameShort;

    /**
     * @ORM\OneToMany(targetEntity="Score" , mappedBy="tournamentId" , cascade={"all"})
     */
    private $scores;

    /**
     * @ORM\OneToMany(targetEntity="Match" , mappedBy="tournamentId" , cascade={"all"})
     */
    private $matches;

    /**
     * @ORM\OneToMany(targetEntity="Team" , mappedBy="tournamentId" , cascade={"all"})
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="PredictionChampion" , mappedBy="tournamentId" , cascade={"all"})
     */
    private $predictionsChampion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scores = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
        $this->predictionsChampion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tournament
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Tournament
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Tournament
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set nameShort
     *
     * @param string $nameShort
     *
     * @return Tournament
     */
    public function setNameShort($nameShort)
    {
        $this->nameShort = $nameShort;

        return $this;
    }

    /**
     * Get nameShort
     *
     * @return string
     */
    public function getNameShort()
    {
        return $this->nameShort;
    }

    /**
     * Add score
     *
     * @param \Devlabs\SportifyBundle\Entity\Score $score
     *
     * @return Tournament
     */
    public function addScore(\Devlabs\SportifyBundle\Entity\Score $score)
    {
        $this->scores[] = $score;

        return $this;
    }

    /**
     * Remove score
     *
     * @param \Devlabs\SportifyBundle\Entity\Score $score
     */
    public function removeScore(\Devlabs\SportifyBundle\Entity\Score $score)
    {
        $this->scores->removeElement($score);
    }

    /**
     * Get scores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * Add match
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $match
     *
     * @return Tournament
     */
    public function addMatch(\Devlabs\SportifyBundle\Entity\Match $match)
    {
        $this->matches[] = $match;

        return $this;
    }

    /**
     * Remove match
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $match
     */
    public function removeMatch(\Devlabs\SportifyBundle\Entity\Match $match)
    {
        $this->matches->removeElement($match);
    }

    /**
     * Get matches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Add team
     *
     * @param \Devlabs\SportifyBundle\Entity\Team $team
     *
     * @return Tournament
     */
    public function addTeam(\Devlabs\SportifyBundle\Entity\Team $team)
    {
        $this->teams[] = $team;

        return $this;
    }

    /**
     * Remove team
     *
     * @param \Devlabs\SportifyBundle\Entity\Team $team
     */
    public function removeTeam(\Devlabs\SportifyBundle\Entity\Team $team)
    {
        $this->teams->removeElement($team);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Add predictionsChampion
     *
     * @param \Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion
     *
     * @return Tournament
     */
    public function addPredictionsChampion(\Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion)
    {
        $this->predictionsChampion[] = $predictionsChampion;

        return $this;
    }

    /**
     * Remove predictionsChampion
     *
     * @param \Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion
     */
    public function removePredictionsChampion(\Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion)
    {
        $this->predictionsChampion->removeElement($predictionsChampion);
    }

    /**
     * Get predictionsChampion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPredictionsChampion()
    {
        return $this->predictionsChampion;
    }
}
