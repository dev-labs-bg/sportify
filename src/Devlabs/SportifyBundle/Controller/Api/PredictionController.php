<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;
use Devlabs\SportifyBundle\Entity\Prediction;
use Devlabs\SportifyBundle\Form\PredictionType;

/**
 * Class PredictionController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class PredictionController extends BaseApiController
{
    protected $entityName = 'Prediction';
    protected $fqEntityClass = Prediction::class;
    protected $repositoryName = 'DevlabsSportifyBundle:Prediction';
    protected $fqEntityFormClass = PredictionType::class;
}
