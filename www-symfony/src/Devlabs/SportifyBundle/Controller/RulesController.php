<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RulesController extends Controller
{
    /**
     * @Route("/rules", name="rules_index")
     */
    public function indexAction()
    {
        // if user is logged in, get their standings
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Get an instance of the Entity Manager
            $em = $this->getDoctrine()->getManager();

            // Load the data for the current user into an object
            $user = $this->getUser();

            // get scores standings for the current user
            $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
                ->getByUser($user);
            $this->container->get('twig')->addGlobal('user_scores', $userScores);
        }

        // rendering the view and returning the response
        return $this->render('Rules/index.html.twig');
    }
}
