<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render(
            'DevlabsSportifyBundle:Default:index.html.twig',
            array()
        );
    }
}
