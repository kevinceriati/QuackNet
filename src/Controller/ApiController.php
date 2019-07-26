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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;


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
        );
    }

    /*
        /**
         * @Route("/api/duck", methods={"POST"})

   public function postNewDucks(Request $request, UserPasswordEncoderInterface $encoder, SerializerInterface $serializer): Response
    {

        $duck = $serializer->deserialize($request->getContent(), Ducks::class,"json");
        $duck->setRoles(['ROLE_USER']);
        $duck->setPassword($encoder->encodePassword($duck, $duck->newpassword));


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($duck);
        $entityManager->flush();
        return $this->json($duck);

    }
*/

    /**
     * @Route("/api/duck", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function postNewDucks(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $content = $request->getContent();

        $data = json_decode($content, true);
        $duck = new Ducks();
        $duck->setFirstname($data["firstname"]);
        $duck->setLastname($data["lastname"]);
        $duck->setDuckname($data["duckname"]);
        $duck->setEmail($data["email"]);
        $duck->setRoles(['ROLE_USER']);
        $duck->setPassword($encoder->encodePassword($duck, $data['password']));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($duck);
        $entityManager->flush();

        return $this->json($duck->jsonSerializeCreatNewUser());
    }


    /**
     * @Route("/api/duck/{duck}", methods={"PUT"})
     */
    public function putDuckEdit(Request $request, UserPasswordEncoderInterface $encoder, Ducks $duck): Response
    {
        $content = $request->getContent();

        $data = json_decode($content, true);

        if (isset($data["firstname"])) {
            $duck->setFirstname($data["firstname"]);
        }

        if (isset($data["lastname"])) {
            $duck->setLastname($data["lastname"]);
        }

        if (isset($data["email"])) {
            $duck->setEmail($data["email"]);
        }

        if (isset($data["password"])) {
            $duck->setPassword($encoder->encodePassword($duck, $data["password"]));
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->json($duck->jsonSerializeUpdateUser());
    }



}