<?php

namespace Devlabs\SportifyBundle\Controller\Api;

use Devlabs\SportifyBundle\Controller\Base\BaseApiController;
use Devlabs\SportifyBundle\Entity\Match;
use Devlabs\SportifyBundle\Form\MatchEntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class MatchController
 * @package Devlabs\SportifyBundle\Controller\Api
 */
class MatchController extends BaseApiController
{
    protected $entityName = 'Match';
    protected $fqEntityClass = Match::class;
    protected $repositoryName = 'DevlabsSportifyBundle:Match';
    protected $fqEntityFormClass = MatchEntityType::class;

    /**
     * Get all users' predictions for a match (ADMIN only)
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getPredictionsAllusersAction($id)
    {
        $match = $this->getDoctrine()->getManager()
            ->getRepository($this->repositoryName)
            ->findOneById($id);

        if (!is_object($match)) {
            return $this->getNotFoundView();
        }

        return $this->view($match->getPredictions(), 200);
    }

    /**
     * Get the user's prediction for a match
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
    public function getPredictionsAction($id)
    {
        // if user is not auth, return unauthorized
        if (!is_object($user = $this->getUser())) {
            return $this->getUnauthorizedView();
        }

        $prediction = $this->getDoctrine()->getManager()
            ->getRepository('DevlabsSportifyBundle:Prediction')
            ->findOneBy(array(
                'matchId' => $id,
                'userId' => $user->getId()
            ));

        if (!is_object($prediction)) {
            return $this->getNotFoundView();
        }

        return $this->view($prediction, 200);
    }
}
