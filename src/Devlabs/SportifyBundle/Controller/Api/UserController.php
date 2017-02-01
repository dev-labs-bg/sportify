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

    /**
     * @param $id
     * @return mixed
     */
    public function getScoresAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        return $object->getScores();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPredictionsAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        return $object->getPredictions();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getChamp_predictionsAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        return $object->getPredictionsChampion();
    }
}
