<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    /**
     * @Rest\Post("/users", name="create_user")
     * @View
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @param UserRepository $userRepository
     * @return User
     */
    public function postUser(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }


    /**
     * @Rest\Put("/users/{id}", name="update_user")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return User
     */
    public function updateUser(User $user, EntityManagerInterface $entityManager)
    {

        $entityManager->flush();

        return $user;
    }

    /**
     * @Rest\Delete("/users/{id}", name="delete_user")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($user);
    }

}
