<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Devlabs\SportifyBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use GuzzleHttp\Client;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function profileAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

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

        // get user standings and set them as global Twig var
        $this->get('app.twig.helper')->setUserScores($user);

        return $this->render(
            'User/profile.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/user/tokens", name="user_tokens")
     */
    public function tokensAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $accessToken = $this->getDoctrine()->getManager()
            ->getRepository('DevlabsSportifyBundle:OAuthAccessToken')
            ->getLastNotExpired($user);

        $formData = array();
        $form = $this->createFormBuilder($formData)
            ->add('password', PasswordType::class)
            ->add('button', SubmitType::class, array(
                'label' => 'Request token'
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $formData = $form->getData();

            $postParams = array(
                'client_id' => $this->getParameter('sportify_api.client_id'),
                'client_secret' => $this->getParameter('sportify_api.client_secret'),
                'grant_type' => 'password',
                'username' => $user->getUsername(),
                'password' => $formData['password']
            );

            // modifying the request
            // and initiating a request to the FOS OAuthServerBundle tokenController
            // in order to generate the access and refresh tokens
            // Sorry for this hackish code :( ... maybe some day I'll fix it

            $request->attributes->replace(array(
                '_controller' => 'FOS\OAuthServerBundle\Controller\TokenController::tokenAction',
                '_route' => 'fos_oauth_server_token'
            ));
            $request->request->replace($postParams);

            $ctrl = new \FOS\OAuthServerBundle\Controller\TokenController(
                $this->get('fos_oauth_server.server')
            );

            $response = $ctrl->tokenAction($request);

            // set a flash message to inform the user of the token request status
            if ($response->getStatusCode() == 200) {
                $flashMsg = 'Successfully generated token.';
            } else {
                $decodedContent = json_decode($response->getContent());
                $flashMsg = $decodedContent->error_description;
            }

            $this->get('session')->getFlashBag()->add('message', $flashMsg);

            return $this->redirectToRoute('user_tokens');
        }

        // get user standings and set them as global Twig var
        $this->get('app.twig.helper')->setUserScores($user);

        return $this->render(
            'User/tokens.html.twig',
            array(
                'access_token' => $accessToken,
                'form' => $form->createView()
            )
        );
    }
}
