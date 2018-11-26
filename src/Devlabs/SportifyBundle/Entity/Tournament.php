<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Intervention\Image\ImageManager;

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
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="tournamentsChampion", cascade={"persist"})
     * @ORM\JoinColumn(name="champion_team_id", referencedColumnName="id")
     */
    private $championTeamId;

    /**
     * @ORM\OneToMany(targetEntity="Score" , mappedBy="tournamentId" , cascade={"all"})
     */
    private $scores;

    /**
     * @ORM\OneToMany(targetEntity="Match" , mappedBy="tournamentId" , cascade={"all"})
     */
    private $matches;

    /**
     * @ORM\ManyToMany(targetEntity="Team", mappedBy="tournaments")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="PredictionChampion" , mappedBy="tournamentId" , cascade={"all"})
     */
    private $predictionsChampion;

    /**
     * Logo
     */
    private $logo;

    /**
     * Temp placeholder for uploaded files
     */
    private $uploadFile;

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
     * Set id
     *
     * @param string $id
     *
     * @return Tournament
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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

    /**
     * Set championTeamId
     *
     * @param \Devlabs\SportifyBundle\Entity\Team $championTeamId
     *
     * @return Tournament
     */
    public function setChampionTeamId(\Devlabs\SportifyBundle\Entity\Team $championTeamId = null)
    {
        $this->championTeamId = $championTeamId;

        return $this;
    }

    /**
     * Get championTeamId
     *
     * @return \Devlabs\SportifyBundle\Entity\Team
     */
    public function getChampionTeamId()
    {
        return $this->championTeamId;
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
     * Check if the tournament already has a logo
     *
     * @return bool $has_logo
     */
    public function hasLogo()
    {
        $jpgFile = WEB_DIRECTORY . '/img/tournament_logos/tournament_logo_'.$this->id.'.jpg';
        $svgFile = WEB_DIRECTORY . '/img/tournament_logos/tournament_logo_'.$this->id.'.svg';

        return ( (file_exists($jpgFile) && is_file($jpgFile)) ||
            (file_exists($svgFile) && is_file($svgFile)) );
    }

    /**
     * Get Tournament Logo
     *
     * @return string $path_to_logo
     */
    public function getLogo()
    {
        $file = WEB_DIRECTORY . '/img/tournament_logos/tournament_logo_'.$this->id.'.png';

        // check if png file exists
        if (file_exists($file) && is_file($file))
            return 'img/tournament_logos/tournament_logo_'.$this->id.'.png';

        // check if svg file exists
        $file = WEB_DIRECTORY . '/img/tournament_logos/tournament_logo_'.$this->id.'.svg';

        if (file_exists($file) && is_file($file))
            return 'img/tournament_logos/tournament_logo_'.$this->id.'.svg';

        return 'img/default_tournament_logo.png';
    }

    /**
     * Set Tournament Logo
     *
     * @return string $path_to_logo
     */
    public function setLogo($filePath = null, $fileExtension = null)
    {
        if (!$filePath)
            return $this;

        /**
         * Skip setting of logo if image/path is NOT valid,
         * and PHP would throw an exception
         */
        try {
            $file = file_get_contents($filePath);
        }
        catch(\Symfony\Component\Debug\Exception\ContextErrorException $e) {
            return $this;
        }

        // delete previous logo file if NOT the default one
        if ($this->getLogo() !== 'img/default_tournament_logo.png') {
            unlink($this->getLogo());
        }

        if (strpos($file, 'svg') !== FALSE || in_array($fileExtension, ['svg', 'svg+xml'])) {
            file_put_contents(WEB_DIRECTORY . '/img/tournament_logos/tournament_logo_' . $this->id . '.svg', $file);
        } else {
            // create an image manager instance with favored driver
            $manager = new ImageManager();
            $image = $manager->make($file);
            $width = $image->width();
            $height = $image->height();

            if ($width >= $height) {
                $image->resize(80, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $image->resize(null, 80, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $image->save(WEB_DIRECTORY . '/img/tournament_logos/tournament_logo_' . $this->id . '.png');
        }

        return $this;
    }

    public function getUploadFile()
    {
        return $this->uploadFile;
    }

    public function setUploadFile($file)
    {
        $this->uploadFile = $file;

        return $this;
    }

    public function __toString() {
        return "$this->id";
    }
}
