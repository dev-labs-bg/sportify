<?php

namespace Devlabs\SportifyBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\UserRepository")
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

    /**
     * @ORM\OneToMany(targetEntity="Prediction" , mappedBy="userId" , cascade={"all"})
     */
    private $predictions;

    /**
     * @ORM\OneToMany(targetEntity="PredictionChampion" , mappedBy="userId" , cascade={"all"})
     */
    private $predictionsChampion;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->scores = new \Doctrine\Common\Collections\ArrayCollection();
        $this->predictions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->predictionsChampion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add score
     *
     * @param \Devlabs\SportifyBundle\Entity\Score $score
     *
     * @return User
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
     * Add prediction
     *
     * @param \Devlabs\SportifyBundle\Entity\Prediction $prediction
     *
     * @return User
     */
    public function addPrediction(\Devlabs\SportifyBundle\Entity\Prediction $prediction)
    {
        $this->predictions[] = $prediction;

        return $this;
    }

    /**
     * Remove prediction
     *
     * @param \Devlabs\SportifyBundle\Entity\Prediction $prediction
     */
    public function removePrediction(\Devlabs\SportifyBundle\Entity\Prediction $prediction)
    {
        $this->predictions->removeElement($prediction);
    }

    /**
     * Get predictions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPredictions()
    {
        return $this->predictions;
    }

    /**
     * Add predictionsChampion
     *
     * @param \Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion
     *
     * @return User
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
