<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Get("/users", name="list_users")
     * @View
     * @param UserRepository $userRepository
     * @return User[]
     */
    public function users(UserRepository $userRepository)
    {
        $allUsers = $userRepository->findAll();
        return $allUsers;
    }


    /**
     * @Get("/users/{id}", name="one_user")
     * @View
     * @param User $user
     * @return User
     */
    public function getOneUser(User $user)
    {
        return $user;
    }
}
