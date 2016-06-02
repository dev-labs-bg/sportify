<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TournamentsController extends Controller
{
    /**
     * @Route("/tournaments")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $tournaments = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->getJoined($user);

        return $this->render(
            'DevlabsSportifyBundle:Tournaments:index.html.twig',
            array('tournaments' => $tournaments)
        );
    }
}
