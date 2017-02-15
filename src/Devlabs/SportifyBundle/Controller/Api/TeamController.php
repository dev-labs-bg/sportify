<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;

/**
 * Class TeamController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class TeamController extends BaseApiController
{
    protected $repositoryName = 'DevlabsSportifyBundle:Team';

    /**
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
