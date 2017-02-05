<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

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

//        if (!is_object($tournament)) {
//            throw new HttpException(404, 'Resource not found.');
//        }

        $scores = $this->getDoctrine()->getManager()
            ->getRepository('DevlabsSportifyBundle:Score')
            ->getByTournamentOrderByPosNew($tournament);

        return $scores;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getMatchesAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        return $object->getMatches();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getTeamsAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        return $object->getTeams();
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
