<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Ducks;
use App\Entity\Quack;
use App\Form\CommentType;
use App\Form\QuackType;
use App\Repository\CommentRepository;
use App\Repository\DucksRepository;
use App\Repository\QuackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ApiController extends AbstractController
{
    /**
     * @route("/api/quack", methods={"GET"})
     */
    public function getFullContentQuacks(QuackRepository $quackRepository)
    {
        $result = $quackRepository->findAll();

        return $this->json($result);
    }


    /**
     * @Route("/api/quack/search", methods={"GET"})
     */
    public function getKeyWordRequest(Request $request, QuackRepository $quackRepository)
    {
        $keyword = $request->query->get('q');
        $result = $quackRepository->searchByKeyword($keyword);

        return $this->json($result);
    }


    /**
     * @route("/api/quack/{quack}", methods={"GET"})
     */
    public function getOneQuackContentAndPicture(Quack $quack)
    {
        return $this->json($quack->jsonSerializeOneQuack());
    }


    /**
     * @Route("/api/quack/{quack}", methods={"DELETE"})
     * @param Request $request
     * @param Quack $quack
     * @return Response
     */
    public function delete(Quack $quack): Response
    {


//        if ($this->isGranted('ROLE_USER' && $quack->getAuthor()->getId() == $this->getUser()->getId())) {
//dd($quack);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quack);
            $entityManager->flush();

//        }
        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );    }



}