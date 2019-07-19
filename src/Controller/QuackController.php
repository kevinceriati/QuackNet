<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Quack;
use App\Form\CommentType;
use App\Form\QuackType;
use App\Repository\CommentRepository;
use App\Repository\QuackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\FileUploader;

/**
 * @Route("/quack")
 */
class QuackController extends AbstractController
{
    /**
     * @Route("/", name="quack_index", methods={"GET"})
     * @Route("/{id}/", name="comment_index", methods={"POST"})
     */
    public function index(QuackRepository $quackRepository, Quack $quack = null, Request $request): Response
    {

        $allForms = $quackRepository->findAll();
//        foreach post creat new form for this post

        foreach ($allForms as $index => $form) {
            $com = new Comment();
            $allForms[$index] = $this->createForm(CommentType::class, $com);
        }

        foreach ($allForms as $form) {
            $form->handleRequest($request);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $com->setAuthor($this->getUser());
            $com->setCreatedAt(new \DateTime('now', (new \DateTimeZone('Europe/Paris'))));
            $com->setQuack($quack);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($com);
            $entityManager->flush();

            return $this->redirectToRoute('quack_index');
        }

        foreach ($allForms as &$form) {
            $form = $form->createView();
        }

        return $this->render('quack/index.html.twig', [
            'quacks' => $quackRepository->findBy(array(), array('created_at' => 'DESC')),
            'allForm' => $allForms
        ]);
    }

    /**
     * @Route("/new", name="quack_new", methods={"GET","POST"})
     * @throws \Exception
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $quack = new Quack();
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quack->setAuthor($this->getUser());
            $quack->setCreatedAt(new \DateTime('now', (new \DateTimeZone('Europe/Paris'))));
            $quackFile = $form['images']->getData();

            if ($quackFile) {
                $quackFileName = $fileUploader->upload($quackFile);
                $quack->setPicture($quackFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quack);
            $entityManager->flush();

            return $this->redirectToRoute('quack_index');
        }

        return $this->render('quack/new.html.twig', [
            'quack' => $quack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quack_show", methods={"GET"})
     */
    public function show(Quack $quack, Request $request): Response
    {

//        $this->denyAccessUnlessGranted('view', $quack);

        return $this->render('quack/show.html.twig', [
            'quack' => $quack,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="quack_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FileUploader $fileUploader, Quack $quack): Response
    {
        $this->denyAccessUnlessGranted('edit', $quack);

        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $quackFile = $form['images']->getData();

            if ($quackFile) {
                $quackFileName = $fileUploader->upload($quackFile);
                $quack->setPicture($quackFileName);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quack_index');

        }

        return $this->render('quack/edit.html.twig', [
            'quack' => $quack,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="quack_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Quack $quack): Response
    {
//        if ($this->isGranted('ROLE_USER' && $quack->getAuthor()->getId() == $this->getUser()->getId())) {

        if ($this->isCsrfTokenValid('delete' . $quack->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quack);
            $entityManager->flush();
        }
//        }
        return $this->redirectToRoute('quack_index');
    }
}
