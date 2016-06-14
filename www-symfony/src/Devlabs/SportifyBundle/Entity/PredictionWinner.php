<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\PredictionWinnerRepository")
 * @ORM\Table(name="predictions_winner", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="user_tournament", columns={"user_id", "tournament_id"})
 * })
 */
class PredictionWinner
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="predictionsWinner")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="predictionsWinner")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private $tournamentId;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="predictionsWinner")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private $teamId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

    /**
     * @ORM\Column(type="boolean", name="score_added")
     */
    private $scoreAdded = 0;

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
     * Set points
     *
     * @param integer $points
     *
     * @return PredictionWinner
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set scoreAdded
     *
     * @param boolean $scoreAdded
     *
     * @return PredictionWinner
     */
    public function setScoreAdded($scoreAdded)
    {
        $this->scoreAdded = $scoreAdded;

        return $this;
    }

    /**
     * Get scoreAdded
     *
     * @return boolean
     */
    public function getScoreAdded()
    {
        return $this->scoreAdded;
    }

    /**
     * Set userId
     *
     * @param \Devlabs\SportifyBundle\Entity\User $userId
     *
     * @return PredictionWinner
     */
    public function setUserId(\Devlabs\SportifyBundle\Entity\User $userId = null)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \Devlabs\SportifyBundle\Entity\User
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set tournamentId
     *
     * @param \Devlabs\SportifyBundle\Entity\Tournament $tournamentId
     *
     * @return PredictionWinner
     */
    public function setTournamentId(\Devlabs\SportifyBundle\Entity\Tournament $tournamentId = null)
    {
        $this->tournamentId = $tournamentId;

        return $this;
    }

    /**
     * Get tournamentId
     *
     * @return \Devlabs\SportifyBundle\Entity\Tournament
     */
    public function getTournamentId()
    {
        return $this->tournamentId;
    }

    /**
     * Set teamId
     *
     * @param \Devlabs\SportifyBundle\Entity\Team $teamId
     *
     * @return PredictionWinner
     */
    public function setTeamId(\Devlabs\SportifyBundle\Entity\Team $teamId = null)
    {
        $this->teamId = $teamId;

        return $this;
    }

    /**
     * Get teamId
     *
     * @return \Devlabs\SportifyBundle\Entity\Team
     */
    public function getTeamId()
    {
        return $this->teamId;
    }
}
