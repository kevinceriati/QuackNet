<?php

namespace App\Controller;

use App\Entity\Ducks;
use App\Form\DucksType;
use App\Repository\DucksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/ducks")
 */
class DucksController extends AbstractController
{
    /**
     * @Route("/", name="ducks_index", methods={"GET"})
     */
    public function index(DucksRepository $ducksRepository): Response
    {
        return $this->render('ducks/index.html.twig', [
            'ducks' => $ducksRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ducks_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $duck = new Ducks();
        $form = $this->createForm(DucksType::class, $duck, ["password_set" => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $duck->setRoles(['ROLE_USER']);

            $duck->setPassword($encoder->encodePassword($duck, $duck->newpassword));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($duck);
            $entityManager->flush();

            return $this->redirectToRoute('ducks_index');
        }

        return $this->render('ducks/new.html.twig', [
            'duck' => $duck,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ducks_show", methods={"GET"})
     */
    public function show(Ducks $duck): Response
    {
        return $this->render('ducks/show.html.twig', [
            'duck' => $duck,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ducks_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ducks $duck, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(DucksType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($duck->newpassword) {
                $duck->setPassword($encoder->encodePassword($duck, $duck->newpassword));
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ducks_index');
        }

        return $this->render('ducks/edit.html.twig', [
            'duck' => $duck,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ducks_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ducks $duck): Response
    {
        if ($this->isCsrfTokenValid('delete' . $duck->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($duck);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ducks_index');
    }



}
