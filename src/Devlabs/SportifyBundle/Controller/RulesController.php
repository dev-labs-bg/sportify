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
        // if user is logged in, get their standings and set them as global Twig var
        if (is_object($user = $this->getUser())) {
            $this->get('app.twig.helper')->setUserScores($user);
        }

        // rendering the view and returning the response
        return $this->render('Rules/index.html.twig');
    }
}
