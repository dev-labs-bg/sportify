<?php

namespace Devlabs\App;

/**
 * Class Match
 * @package Devlabs\App
 */
class Match
{
    public $id;
    public $datetime;
    public $homeTeam;
    public $awayTeam;
    public $homeGoals;
    public $awayGoals;
    public $tournamentId;
    public $disabled = '';

    public function __construct($id = '', $datetime = '', $homeTeam = '', $awayTeam = '',
                                $homeGoals = '', $awayGoals = '', $tournamentId = '', $disabled = '')
    {
        $this->id = $id;
        $this->datetime = $datetime;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->homeGoals = $homeGoals;
        $this->awayGoals = $awayGoals;
        $this->tournamentId = $tournamentId;
        $this->disabled = $disabled;
    }

    /**
     * Load a match's data from database by passing in its id
     *
     * @param $id
     */
    public function loadById($id)
    {
        $query = $GLOBALS['db']->query(
            "SELECT * FROM matches WHERE id = :id",
            array('id' => $id)
        );

        if ($query) {
            $this->id = $query[0]['id'];
            $this->datetime = $query[0]['datetime'];
            $this->homeTeam = $query[0]['home_team'];
            $this->awayTeam = $query[0]['away_team'];
            $this->homeGoals = $query[0]['home_goals'];
            $this->awayGoals = $query[0]['away_goals'];
            $this->tournamentId = $query[0]['tournament_id'];
        }
    }

    /**
     * Check if match has started by comparing the current time with the match's datetime
     *
     * @return bool
     */
    public function hasStarted()
    {
        return (time() >= strtotime($this->datetime));
    }

    /**
     * If the match has already started, set the disabled property to prevent further prediction changes
     */
    public function setDisabled()
    {
        if ($this->hasStarted()) {
            $this->disabled = 'disabled';
        }
    }
}