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
        $userScores = array();

        // if user is logged in, get his standings
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Get an instance of the Entity Manager
            $em = $this->getDoctrine()->getManager();

            // Load the data for the current user into an object
            $user = $this->getUser();

            // get scores standings for the current user
            $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
                ->getByUser($user);
        }

        $twig = $this->container->get('twig');
        $twig->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render('DevlabsSportifyBundle:Rules:index.html.twig');
    }
}
