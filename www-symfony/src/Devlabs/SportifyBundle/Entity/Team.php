<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Routing\RequestContext;
use Intervention\Image\ImageManager;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\TeamRepository")
 * @ORM\Table(name="teams")
 * @UniqueEntity("name")
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
     * @ORM\ManyToMany(targetEntity="Tournament", inversedBy="teams")
     * @ORM\JoinTable(name="teams_tournaments")
     */
    private $tournaments;

    /**
     * @ORM\OneToMany(targetEntity="PredictionChampion" , mappedBy="teamId" , cascade={"all"})
     */
    private $predictionsChampion;

    /**
     * @ORM\OneToMany(targetEntity="Tournament" , mappedBy="championTeamId" , cascade={"all"})
     */
    private $tournamentsChampion;

    /**
     * @ORM\OneToMany(targetEntity="Match" , mappedBy="homeTeamId" , cascade={"all"})
     */
    private $matchesHomeTeam;

    /**
     * @ORM\OneToMany(targetEntity="Match" , mappedBy="awayTeamId" , cascade={"all"})
     */
    private $matchesAwayTeam;

    /**
     * Team logo
     */
    private $teamLogo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tournaments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->predictionsChampion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tournamentsChampion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matchesHomeTeam = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matchesAwayTeam = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Team
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
     * Add predictionsChampion
     *
     * @param \Devlabs\SportifyBundle\Entity\PredictionChampion $predictionsChampion
     *
     * @return Team
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
     * Add matchesHomeTeam
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $matchesHomeTeam
     *
     * @return Team
     */
    public function addMatchesHomeTeam(\Devlabs\SportifyBundle\Entity\Match $matchesHomeTeam)
    {
        $this->matchesHomeTeam[] = $matchesHomeTeam;

        return $this;
    }

    /**
     * Remove matchesHomeTeam
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $matchesHomeTeam
     */
    public function removeMatchesHomeTeam(\Devlabs\SportifyBundle\Entity\Match $matchesHomeTeam)
    {
        $this->matchesHomeTeam->removeElement($matchesHomeTeam);
    }

    /**
     * Get matchesHomeTeam
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchesHomeTeam()
    {
        return $this->matchesHomeTeam;
    }

    /**
     * Add matchesAwayTeam
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $matchesAwayTeam
     *
     * @return Team
     */
    public function addMatchesAwayTeam(\Devlabs\SportifyBundle\Entity\Match $matchesAwayTeam)
    {
        $this->matchesAwayTeam[] = $matchesAwayTeam;

        return $this;
    }

    /**
     * Remove matchesAwayTeam
     *
     * @param \Devlabs\SportifyBundle\Entity\Match $matchesAwayTeam
     */
    public function removeMatchesAwayTeam(\Devlabs\SportifyBundle\Entity\Match $matchesAwayTeam)
    {
        $this->matchesAwayTeam->removeElement($matchesAwayTeam);
    }

    /**
     * Get matchesAwayTeam
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchesAwayTeam()
    {
        return $this->matchesAwayTeam;
    }

    /**
     * Add tournamentsChampion
     *
     * @param \Devlabs\SportifyBundle\Entity\Tournament $tournamentsChampion
     *
     * @return Team
     */
    public function addTournamentsChampion(\Devlabs\SportifyBundle\Entity\Tournament $tournamentsChampion)
    {
        $this->tournamentsChampion[] = $tournamentsChampion;

        return $this;
    }

    /**
     * Remove tournamentsChampion
     *
     * @param \Devlabs\SportifyBundle\Entity\Tournament $tournamentsChampion
     */
    public function removeTournamentsChampion(\Devlabs\SportifyBundle\Entity\Tournament $tournamentsChampion)
    {
        $this->tournamentsChampion->removeElement($tournamentsChampion);
    }

    /**
     * Get tournamentsChampion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTournamentsChampion()
    {
        return $this->tournamentsChampion;
    }

    /**
     * Add tournament
     *
     * @param \Devlabs\SportifyBundle\Entity\Tournament $tournament
     *
     * @return Team
     */
    public function addTournament(\Devlabs\SportifyBundle\Entity\Tournament $tournament)
    {
        $this->tournaments[] = $tournament;

        return $this;
    }

    /**
     * Remove tournament
     *
     * @param \Devlabs\SportifyBundle\Entity\Tournament $tournament
     */
    public function removeTournament(\Devlabs\SportifyBundle\Entity\Tournament $tournament)
    {
        $this->tournaments->removeElement($tournament);
    }

    /**
     * Get tournaments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTournaments()
    {
        return $this->tournaments;
    }

    /**
     * Check if the team already has a logo
     *
     * @return bool $has_logo
     */
    public function hasTeamLogo()
    {
        $jpgFile = WEB_DIRECTORY . '/img/team_logos/team_logo_'.$this->id.'.jpg';
        $svgFile = WEB_DIRECTORY . '/img/team_logos/team_logo_'.$this->id.'.svg';

        return ( (file_exists($jpgFile) && is_file($jpgFile)) ||
                 (file_exists($svgFile) && is_file($svgFile)) );
    }

    /**
     * Get Team Logo
     *
     * @return string $path_to_logo
     */
    public function getTeamLogo()
    {
        $file = WEB_DIRECTORY . '/img/team_logos/team_logo_'.$this->id.'.png';

        // check if png file exists
        if (file_exists($file) && is_file($file))
            return 'img/team_logos/team_logo_'.$this->id.'.png';

        // check if svg file exists
        $file = WEB_DIRECTORY . '/img/team_logos/team_logo_'.$this->id.'.svg';

        if (file_exists($file) && is_file($file))
            return 'img/team_logos/team_logo_'.$this->id.'.svg';

        return 'img/default.png';
    }

    /**
     * Set Team Logo
     *
     * @return string $path_to_logo
     */
    public function setTeamLogo($imagePath)
    {
        /**
         * Skip setting of TeamLogo if image/path is NOT valid,
         * and PHP would throw an exception
         */
        try {
            $file = file_get_contents($imagePath);
        }
        catch(\Symfony\Component\Debug\Exception\ContextErrorException $e) {
            return $this;
        }

        if (strpos($file, 'svg') !== FALSE) {
            file_put_contents(WEB_DIRECTORY . '/img/team_logos/team_logo_' . $this->id . '.svg', $file);
        } else {
            // create an image manager instance with favored driver
            $manager = new ImageManager();
            $image = $manager->make($file);
            $width = $image->width();
            $height = $image->height();

            if ($width >= $height) {
                $image->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $image->resize(null, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $image->save(WEB_DIRECTORY . '/img/team_logos/team_logo_' . $this->id . '.png');
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
