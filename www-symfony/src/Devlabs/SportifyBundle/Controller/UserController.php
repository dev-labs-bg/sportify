<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Devlabs\SportifyBundle\Form\UserType;

class UserController extends Controller
{
	/**
	 * @Route("/user/profile", name="user_profile")
	 */
	public function profileAction()
	{
		$user = $this->getUser();
		$form = $this->createForm(UserType::class, $user, array(
			'action' => $this->generateUrl('user_update'),
			'method' => 'POST',
		));

		return $this->render(
			'DevlabsSportifyBundle:User:profile.html.twig',
			array(
				'form' => $form->createView()
			)
		);
	}

	/**
	 * @Route("/user/update", name="user_update")
	 */
	public function updateAction(Request $request)
	{

	}
}
