<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

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

        return $object->getTournaments();
    }
}
