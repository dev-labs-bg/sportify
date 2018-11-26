<?php

namespace Devlabs\SportifyBundle\Controller\Base;

/**
 * Class ExceptionController
 * @package Devlabs\SportifyBundle\Controller\Base
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