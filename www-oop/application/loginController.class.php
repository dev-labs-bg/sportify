<?php

namespace devlabs\app;

class loginController extends abstractController
{
    public function index()
    {
        return new view('login');
    }
}
