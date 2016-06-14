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
     * @ORM\OneToMany(targetEntity="PredictionWinner" , mappedBy="userId" , cascade={"all"})
     */
    private $predictionsWinner;

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
     * Add predictionsWinner
     *
     * @param \Devlabs\SportifyBundle\Entity\PredictionWinner $predictionsWinner
     *
     * @return User
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
