<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Form\UserType;

class UserController extends Controller
{
	/**
	 * @Route("/user/profile", name="user_profile")
	 */
	public function profileAction(Request $request)
	{
		// if user is not logged in, redirect to login page
		$securityContext = $this->container->get('security.authorization_checker');
		if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			return $this->redirectToRoute('fos_user_security_login');
		}

		$user = $this->getUser();

		$form = $this->createForm(UserType::class, $user, array(
			'action' => $this->generateUrl('user_profile'),
			'method' => 'POST',
		));

		$form->handleRequest($request);

		if ($form->isValid()) {
			$userManager = $this->container->get('fos_user.user_manager');
			$user->setPlainPassword($form->get('password')->getData());
			$userManager->updateUser($user, true);

			$this->addFlash(
				'notice',
				'Your profile was updated successfully!'
			);
		}

		// get the user's tournaments position data
		$em = $this->getDoctrine()->getManager();
		$userScores = $em->getRepository('DevlabsSportifyBundle:Score')
			->getByUser($user);
		$this->container->get('twig')->addGlobal('user_scores', $userScores);

		return $this->render(
			'User/profile.html.twig',
			array(
				'form' => $form->createView()
			)
		);
	}
}
