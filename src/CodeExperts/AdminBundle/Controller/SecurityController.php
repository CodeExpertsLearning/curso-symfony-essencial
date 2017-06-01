<?php

namespace CodeExperts\AdminBundle\Controller;

use CodeExperts\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AdminBundle:security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/new-user", name="new_user")
     */
    public function newAction(Request $request)
    {
        $user = new User();

        $password = $this->get('security.password_encoder')->encodePassword($user, '123456');

        $user->setName("Nanderson");
        $user->setPassword($password);
        $user->setRoles('ROLE_ADMIN');
        $user->setEmail('nandokstro@gmail.com');
        $user->setCreatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")));
        $user->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")));

        $doctrine = $this->getDoctrine();
        $doctrine->getManager()->persist($user);
        $doctrine->getManager()->flush();
    }
}
