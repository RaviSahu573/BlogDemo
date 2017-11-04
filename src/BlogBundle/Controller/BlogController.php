<?php

/*
 * This file is BlogBundle.
 */

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
use BlogBundle\Entity\Post;
use BlogBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller used to manage blog contents in the public part of the site.
 *
 * @Route("/blog")
 *
 * @author Ravi Sahu
 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="blog_index")
     * @Route("/")
     * @Method("GET")
     *
     */
    public function indexAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findAll();
        return $this->render('BlogBundle:blog:index.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/posts/{slug}", name="blog_post")
     * @Method("GET")
     */
    public function postShowAction(Post $post): Response
    {


        return $this->render('BlogBundle:blog:post_show.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/comment/{postSlug}/new", name="comment_new")
     * @Method("POST")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @ParamConverter("post", options={"mapping": {"postSlug": "slug"}})
     *
     */
    public function commentNewAction(Request $request, Post $post): Response
    {
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();


            return $this->redirectToRoute('blog_post', ['slug' => $post->getSlug()]);
        }

        return $this->render('blog/comment_form_error.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * This controller is called directly via the render() function in the
     * blog/post_show.html.twig template. That's why it's not needed to define
     * a route name for it.
     *
     * The "id" of the Post is passed in and then turned into a Post object
     * automatically by the ParamConverter.
     */
    public function commentFormAction(Post $post): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('BlogBundle:blog:_comment_form.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

}
