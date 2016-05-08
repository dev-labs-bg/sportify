<?php

namespace Devlabs\App;

class Tournament
{
    public $id;
    public $name;
    public $startDate;
    public $endDate;
    public $seleted;

    public function __construct($id, $name, $start, $end, $selected = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->startDate = $start;
        $this->endDate = $end;
        $this->seleted = $selected;
    }
}