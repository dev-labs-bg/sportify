<?php

namespace devlabs\app;

class registerController extends abstractController
{
    public function index()
    {
        return new view('register');
    }
}
