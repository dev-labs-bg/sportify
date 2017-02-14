<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;
use Devlabs\SportifyBundle\Entity\Prediction;
use Devlabs\SportifyBundle\Form\PredictionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

    /**
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function cgetAllusersAction()
    {
//        // allow access to ADMIN users only
//        if (!$this->isGranted('ROLE_ADMIN')) {
//            return $this->view(null, 401);
//        }

        $objects = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findAll();

        return $this->view($objects, 200);
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    public function cgetAction()
    {
        // if user is not auth, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->view(null, 401);
        }

        // get user's predictions
        $objects = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findBy(array(
                'userId' => $user->getId()
            ));

        return $this->view($objects, 200);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getAction($id)
    {
        // if user is not logged in, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->view(null, 401);
        }

        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->view(null, 404);
        }

        // restrict normal user to be able to see only their data
        if (!$this->isGranted('ROLE_ADMIN') && $user != $object->getUserId()) {
            return $this->view(null, 403);
        }

        return $this->view($object, 200);
    }
}
