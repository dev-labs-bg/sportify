<?php

namespace Devlabs\App;

class RegisterController extends AbstractController
{
    public function index()
    {
        return new view('register');
    }
}
