<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\CommentTypeSafeDelete;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment", methods={"GET"})
     * @Route("comment/{id}/hide", name="comment_hide", methods={"POST"})
     */
    public function index(CommentRepository $commentRepository, Comment $comment = null, Request $request)
    {
        $allForms = $commentRepository->findAll();

        foreach ($allForms as $index => $form) {
            $com = new Comment();
            $allForms[$index] = $this->createForm(CommentTypeSafeDelete::class, $com);
        }

        foreach ($allForms as $form) {
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if($comment->getIsDeleted() == false) {
                $comment->setIsDeleted(true);
            } else {
                $comment->setIsDeleted(false);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment');
        }
        foreach ($allForms as &$form) {
            $form = $form->createView();
        }

        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findBy(array(), array('createdAt' => 'DESC')),
            'form' => $allForms
        ]);
    }


    /**
     * @Route("comment/{id}/edit", name="comment_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $this->denyAccessUnlessGranted('edit', $comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quack_index');
        }

        return $this->render('comment/edit.html.twig', [
            'quack' => $comment->getQuack(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
//        if ($this->isGranted('ROLE_USER' && $quack->getAuthor()->getId() == $this->getUser()->getId())) {

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('quack_index');
    }

}
