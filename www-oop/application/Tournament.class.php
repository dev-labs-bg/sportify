<?php

namespace Devlabs\App;

class Tournament
{
    public $id;
    public $name;
    public $startDate;
    public $endDate;

    public function __construct($id, $name, $start, $end)
    {
        $this->id = $id;
        $this->name = $name;
        $this->startDate = $start;
        $this->endDate = $end;
    }
}