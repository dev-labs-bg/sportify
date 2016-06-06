<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
	/**
	 * @Route("/user/profile", name="user_profile")
	 */
	public function profileAction()
	{
		$user = $this->getUser();
		return $this->render(
			'DevlabsSportifyBundle:User:profile.html.twig'
		);
	}
}
