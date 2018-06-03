<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/comment")
 */
class CommentController extends Controller
{

    /**
     * @Route("/{article_id}/new-comment", name="add_article_comment", methods="POST")
     *
     * @param Request $request
     * @param int $article_id
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function commentOnArticle(Request $request, int $article_id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($article_id);

        if(!$article){
            throw $this->createNotFoundException("The article that your are trying to comment does't exist.");
        }

        $content = $request->request->get('comment-content');

        // Delete spaces
        $content = trim($content);

        // The comment cant be empty
        if(!$content || strlen($content) < 3 ){

            $this->addFlash('warning', 'The comment should have at least 3 characters.');

            return $this->redirectToRoute('article_show', array('id' => $article->getId()));
        }

        // Create the comment from content
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setArticle($article);
        $comment->setContent($content);

        // Save the comment
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $this->addFlash('success', 'The comment has been added.');

        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    }

    /**
     * @Route("/", name="comment_index", methods="GET")
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', ['comments' => $commentRepository->findAll()]);
    }

    /**
     * @Route("/new", name="comment_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_show", methods="GET")
     *
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', ['comment' => $comment]);
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods="GET|POST")
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setModifiedBy($this->getUser());
            $comment->setLastModifiedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The comment has been edited.');

            return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_delete", methods="DELETE")
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function delete(Request $request, Comment $comment): Response
    {
        $articleId = $comment->getArticle()->getId();

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }

        return $this->redirectToRoute('article_show', ['id' => $articleId]);
    }
}
