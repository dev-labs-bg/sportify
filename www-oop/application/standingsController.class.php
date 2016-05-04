<?php

namespace devlabs\app;

class standingsController extends abstractController
{
    public function index()
    {
        return new view('standings');
    }
}
