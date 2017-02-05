<?php

namespace Devlabs\SportifyBundle\Controller\Api;

/**
 * Class ExceptionController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class ExceptionController
{
    public function showAction()
    {
        return [
            'message' => 'Error'
        ];
    }
}