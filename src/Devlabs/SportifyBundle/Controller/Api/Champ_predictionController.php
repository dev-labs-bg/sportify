<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

/**
 * Class Champ_predictionController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class Champ_predictionController extends BaseApiController
{
    protected $repositoryName = 'DevlabsSportifyBundle:PredictionChampion';
}
