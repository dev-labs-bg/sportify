<?php

namespace Devlabs\SportifyBundle\Controller;

use Devlabs\SportifyBundle\Entity\User;
use Devlabs\SportifyBundle\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TournamentsController extends Controller
{
    /**
     * @Route("/tournaments")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $tournaments = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->getJoined($user);

        $forms = array();

        if ($tournaments) {
            foreach ($tournaments as $tournament) {
                $formData = array();
                $form = $this->createFormBuilder($formData)
                    ->add('id', HiddenType::class, array('data' => $tournament->getId()))
                    ->add('leave', SubmitType::class, array('label' => 'Leave'))
                    ->getForm();

                $form->handleRequest($request);
                $forms[$tournament->getId()] = $form;
            }

            foreach ($forms as $form) {
                if ($form->isSubmitted() && $form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
                    $formData = $form->getData();
//                    var_dump($formData);
                    $tournament = $this->getDoctrine()
                        ->getRepository('DevlabsSportifyBundle:Tournament')
                        ->findOneById($formData['id']);

                    if ($tournament) $em->remove($tournament);

                    // execute the queries
                    $em->flush();

//                    unset($forms[$formData['id']]);
//                    unset($tournaments);
                }
            }

            $tournaments = $em->getRepository('DevlabsSportifyBundle:Tournament')
                ->getJoined($user);

            foreach ($tournaments as $tournament) {
                $forms[$tournament->getId()] = $forms[$tournament->getId()]->createView();
            }
        }

        return $this->render(
            'DevlabsSportifyBundle:Tournaments:index.html.twig',
            array(
                'tournaments' => $tournaments,
                'forms' => $forms
            )
        );
    }
}
