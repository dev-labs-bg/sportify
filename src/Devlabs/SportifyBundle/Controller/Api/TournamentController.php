<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

/**
 * Class TournamentController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class TournamentController extends BaseApiController
{
    protected $repositoryName = 'DevlabsSportifyBundle:Tournament';
}
