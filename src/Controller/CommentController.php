<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Quack;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index()
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }


    /**
     * @Route("/new", name="comment_new", methods={"GET","POST"})
     * @throws \Exception
     */
    public function new(Request $request, Quack $quack): Response
    {
//        $com = new Comment();
//        $form = $this->createForm(CommentType::class, $com);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $com->setAuthor($this->getUser());
//            $com->setCreatedAt(new \DateTime('now', (new \DateTimeZone('Europe/Paris'))));
//            $com->setQuack($quack);
//
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($com);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('quack_index');
//        }
//
//        return $this->render('quack/new.html.twig', [
//            'quack' => $com,
//            'form' => $form->createView(),
//        ]);
    }



}
