<?php

namespace Devlabs\App;

class Tournament
{
    public $id;
    public $name;
    public $startDate;
    public $endDate;
    public $seleted = '';

    public function __construct($id, $name, $start, $end, $selected = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->startDate = $start;
        $this->endDate = $end;
        $this->seleted = $selected;
    }

    public function loadById($tournament_id)
    {
        $query = $GLOBALS['db']->query(
            "SELECT * FROM tournaments WHERE id = :tournament_id",
            array('tournament_id' => $tournament_id)
        );

        if ($query) {
            $this->id = $query[0]['id'];
            $this->name = $query[0]['name'];
            $this->startDate = $query[0]['start'];
            $this->endDate = $query[0]['end'];
        }
    }

    public function loadByName($tournament_name)
    {
        $query = $GLOBALS['db']->query(
            "SELECT * FROM tournaments WHERE name = :tournament_name",
            array('tournament_name' => $tournament_name)
        );

        if ($query) {
            $this->id = $query[0]['id'];
            $this->name = $query[0]['name'];
            $this->startDate = $query[0]['start'];
            $this->endDate = $query[0]['end'];
        }
    }
}