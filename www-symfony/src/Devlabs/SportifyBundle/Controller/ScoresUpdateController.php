<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ScoresUpdateController
 * @package Devlabs\SportifyBundle\Controller
 */
class ScoresUpdateController extends Controller
{
    /**
     * @Route("/scores_update",
     *     name="scores_update_index"
     * )
     */
    public function indexAction(Request $request)
    {
        // redirect to the Home page
        return $this->redirectToRoute('home');
    }
}
