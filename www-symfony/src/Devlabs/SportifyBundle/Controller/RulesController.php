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
        // rendering the view and returning the response
        return $this->render('DevlabsSportifyBundle:Rules:index.html.twig');
    }
}
