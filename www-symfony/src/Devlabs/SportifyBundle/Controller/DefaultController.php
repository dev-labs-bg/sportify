<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tournaments = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->getJoined();

        return $this->render(
            'DevlabsSportifyBundle:Default:index.html.twig',
            array('tournaments' => $tournaments)
        );
    }
}
