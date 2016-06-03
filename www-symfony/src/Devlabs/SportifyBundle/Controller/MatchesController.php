<?php

namespace Devlabs\SportifyBundle\Controller;

use Devlabs\SportifyBundle\Entity\Score;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class MatchesController
 * @package Devlabs\SportifyBundle\Controller
 */
class MatchesController extends Controller
{
    /**
     * @Route("/matches", name="matches_index")
     */
    public function indexAction(Request $request)
    {
        // Load the data for the current user into an object
        $user = $this->getUser();

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get all and joined tournaments lists
        $matches = $em->getRepository('DevlabsSportifyBundle:Matches')
            ->findAll();
        $tournamentsJoined = $em->getRepository('DevlabsSportifyBundle:Matches')
            ->getJoined($user);

        $forms = array();

        if ($matches) {
        }

        // rendering the view and returning the response
        return $this->render(
            'DevlabsSportifyBundle:Matches:index.html.twig',
            array(
                'matches' => $matches,
                'predictions' => $predictions,
                'forms' => $forms
            )
        );
    }
}
