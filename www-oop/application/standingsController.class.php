<?php

class standingsController extends abstractController
{
    public function index()
    {
        return new view('standings');
    }
}
