<?php

namespace App\Controller;

use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="list_users")
     * @param UserRepository $userRepository
     * @param SerializerInterface $serialize
     * @return JsonResponse
     */
    public function users(UserRepository $userRepository, SerializerInterface $serialize )
    {
        // collect all data from User
        $users = $userRepository->findAll();

        //serialize collected data
        $userSerialized = $serialize->serialize($users, "json");

        return new JsonResponse($userSerialized, 200, [], true);
    }


    /**
     * @Route("/users/{id}", name="one_user")
     * @param $id
     * @param UserRepository $userRepository
     * @param SerializerInterface $serialize
     * @return JsonResponse
     */
    public function getOneUser($id, UserRepository $userRepository, SerializerInterface $serialize )
    {
        // collect a specific user
        $user = $userRepository->findOneById($id);

        //serialize collected data
        $userSerialized = $serialize->serialize($user, "json");

        return new JsonResponse($userSerialized, 200, [], true);
    }
}
