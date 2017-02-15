<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use Symfony\Component\HttpFoundation\Request;
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
//            return $this->view(null, 403);
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
            return $this->getUnauthorizedView();
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
            return $this->getUnauthorizedView();
        }

        $object = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($object)) {
            return $this->getNotFoundView();
        }

        // restrict normal user to be able to see only their data
        if (!$this->isGranted('ROLE_ADMIN') && $user != $object->getUserId()) {
            return $this->view(null, 403);
        }

        return $this->view($object, 200);
    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postAction(Request $request)
    {
        return parent::postAction($request);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function putAction(Request $request, $id)
    {
        return parent::putAction($request, $id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function patchAction(Request $request, $id)
    {
        return parent::patchAction($request, $id);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }
}
