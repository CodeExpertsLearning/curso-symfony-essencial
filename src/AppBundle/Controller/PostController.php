<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/posts")
 * @package AppBundle\Controller
 */
class PostController extends Controller
{

    /**
     * @Route("/")
     * @return Response
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()->getRepository("AppBundle:Post")
                        ->findAll();

        return $this->render('posts/posts.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/create")
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $post = $form->getData();
            $post->setCreatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")));
            $post->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")));

            $doctrine = $this->getDoctrine()->getEntityManager();

            $doctrine->persist($post);
            $doctrine->flush();

            $this->addFlash("success", "Post inserido com sucesso!");

            return $this->redirect('/posts/');
        }

        return $this->render('posts/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}")
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function editAction(Post $post, Request $request)
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $post = $form->getData();
            $post->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")));

            $doctrine = $this->getDoctrine()->getEntityManager();

            $doctrine->persist($post);
            $doctrine->flush();

            $this->addFlash("success", "Post editado com sucesso!");

            return $this->redirect('/posts/edit/' . $post->getId());
        }

        return $this->render('posts/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/remove/{post}")
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function removeAction(Post $post)
    {
        $this->getDoctrine()->getEntityManager()->remove($post);
        $this->getDoctrine()->getEntityManager()->flush();


        $this->addFlash("warning", "Post editado com sucesso!");

        return $this->redirect('/posts');
    }

    /**
     * @Route("/post/{slug}")
     * @return Response
     */
    public function singleAction($slug)
    {
        $data = ['slug' => $slug];

        return $this->render('posts/single.html.twig', $data);
    }
}
