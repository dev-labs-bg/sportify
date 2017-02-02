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

    /**
     * @param $id
     * @return mixed
     */
    public function getScoresAction($id)
    {
        $tournament = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        $scores = $this->getDoctrine()->getManager()
            ->getRepository('DevlabsSportifyBundle:Score')
            ->getByTournamentOrderByPosNew($tournament);

        return $scores;
    }
}
