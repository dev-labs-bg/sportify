<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminController extends Controller
{
    /**
     * @Route("/admin/index", name="admin_index")
     */
    public function indexAction()
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // continue only if user is part of Admin Users list, else redirect to Home
//        if (!in_array($user->getEmail(), $this->container->getParameter('admin.users'))) {
//            return $this->redirectToRoute('home');
//        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render('Admin/index.html.twig');
    }

    /**
     * @Route("/admin/data_updates", name="admin_data_updates")
     */
    public function dataUpdatesAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // continue only if user is part of Admin Users list, else redirect to Home
//        if (!in_array($user->getEmail(), $this->container->getParameter('admin.users'))) {
//            return $this->redirectToRoute('home');
//        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the filter helper service
        $adminHelper = $this->container->get('app.admin.helper');

        // create form for Data Update type select and handle it
        $form = $adminHelper->createDataUpdatesForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminHelper->actionOnDataUpdatesFormSubmit($form);

            return $this->redirectToRoute('admin_data_updates');
        }

        // get the user's tournaments position data
        $userScores = $em->getRepository('DevlabsSportifyBundle:Score')
            ->getByUser($user);
        $this->container->get('twig')->addGlobal('user_scores', $userScores);

        // rendering the view and returning the response
        return $this->render(
            'Admin/data_updates.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}
