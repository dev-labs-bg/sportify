<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

/**
 * Class UserController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class UserController extends BaseApiController
{
    protected $repositoryName = 'DevlabsSportifyBundle:User';
}
