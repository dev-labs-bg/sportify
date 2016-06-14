<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\TeamRepository")
 * @ORM\Table(name="teams")
 * @UniqueEntity("name")
 * @UniqueEntity("nameShort")
 */
class Team
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique = true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10, name="name_short", unique = true)
     */
    private $nameShort;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="teams")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private $tournamentId;

    /**
     * @ORM\OneToMany(targetEntity="PredictionWinner" , mappedBy="teamId" , cascade={"all"})
     */
    private $predictionsWinner;

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
     * @return Team
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
     * Set nameShort
     *
     * @param string $nameShort
     *
     * @return Team
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
     * Set tournamentId
     *
     * @param \Devlabs\SportifyBundle\Entity\Tournament $tournamentId
     *
     * @return Team
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
     * Constructor
     */
    public function __construct()
    {
        $this->predictionsWinner = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add predictionsWinner
     *
     * @param \Devlabs\SportifyBundle\Entity\PredictionWinner $predictionsWinner
     *
     * @return Team
     */
    public function addPredictionsWinner(\Devlabs\SportifyBundle\Entity\PredictionWinner $predictionsWinner)
    {
        $this->predictionsWinner[] = $predictionsWinner;

        return $this;
    }

    /**
     * Remove predictionsWinner
     *
     * @param \Devlabs\SportifyBundle\Entity\PredictionWinner $predictionsWinner
     */
    public function removePredictionsWinner(\Devlabs\SportifyBundle\Entity\PredictionWinner $predictionsWinner)
    {
        $this->predictionsWinner->removeElement($predictionsWinner);
    }

    /**
     * Get predictionsWinner
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPredictionsWinner()
    {
        return $this->predictionsWinner;
    }
}
