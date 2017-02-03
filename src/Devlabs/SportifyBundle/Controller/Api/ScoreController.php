<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

/**
 * Class ScoreController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class ScoreController extends BaseApiController
{
    protected $repositoryName = 'DevlabsSportifyBundle:Score';
}
