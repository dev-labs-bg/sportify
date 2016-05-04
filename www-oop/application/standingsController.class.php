<?php

namespace Devlabs\App;

class StandingsController extends AbstractController
{
    public function index()
    {
        return new view('standings');
    }
}
