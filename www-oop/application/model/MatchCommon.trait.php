<?php

namespace Devlabs\App;

trait MatchCommon
{
    /**
     * Get the outcome of the match
     *
     * @return string
     */
    public function getResultOutcome()
    {
        if ($this->homeGoals > $this->awayGoals) {
            return '1';
        } else if ($this->homeGoals < $this->awayGoals) {
            return '2';
        }

        return 'X';
    }
}