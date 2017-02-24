<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use Devlabs\SportifyBundle\Entity\Tournament;
use Devlabs\SportifyBundle\Form\TournamentEntityType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class TournamentController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class TournamentController extends BaseApiController
{
    protected $entityName = 'Tournament';
    protected $fqEntityClass = Tournament::class;
    protected $repositoryName = 'DevlabsSportifyBundle:Tournament';
    protected $fqEntityFormClass = TournamentEntityType::class;

    /**
     * Get a tournament's scores/standings table
     *
     * @ApiDoc(
     *     statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getScoresAction($id)
    {
        $tournament = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($tournament)) {
            return $this->getNotFoundView();
        }

        $scores = $this->getDoctrine()->getManager()
            ->getRepository('DevlabsSportifyBundle:Score')
            ->getByTournamentOrderByPosNew($tournament);

        return $this->view($scores, 200);
    }

    /**
     * Get a tournament's matches
     *
     * @ApiDoc(
     *     statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getMatchesAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->view($object->getMatches(), 200);
    }

    /**
     * Get a tournament's teams
     *
     * @ApiDoc(
     *     statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getTeamsAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->view($object->getTeams(), 200);
    }

    /**
     * Get a tournament's user champion predictions
     *
     * @ApiDoc(
     *     statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when request is not authenticated",
     *      403 = "Returned when request is not allowed for provided token/user",
     *      404 = "Returned when resource not found"
     *     }
     * )
     *
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getChamp_predictionsAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->view($object->getPredictionsChampion(), 200);
    }
}
