<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use Devlabs\SportifyBundle\Entity\PredictionChampion;

/**
 * Class Champ_predictionController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class Champ_predictionController extends BaseApiController
{
    protected $entityName = 'PredictionChampion';
    protected $fqEntityClass = PredictionChampion::class;
    protected $repositoryName = 'DevlabsSportifyBundle:PredictionChampion';
    protected $fqEntityFormClass = PredictionChampionEntityType::class;
}
