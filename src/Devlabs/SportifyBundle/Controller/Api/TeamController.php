<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use Devlabs\SportifyBundle\Entity\Team;
use Devlabs\SportifyBundle\Form\TeamEntityType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class TeamController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class TeamController extends BaseApiController
{
    protected $entityName = 'Team';
    protected $fqEntityClass = Team::class;
    protected $repositoryName = 'DevlabsSportifyBundle:Team';
    protected $fqEntityFormClass = TeamEntityType::class;

    /**
     * Get a team's tournaments
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
     * @return mixed
     */
    public function getTournamentsAction($id)
    {
        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        return $this->view($object->getTournaments(), 200);
    }
}
