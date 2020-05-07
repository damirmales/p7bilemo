<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;


class UserController extends AbstractController
{
    private $userRepo;

    public function __construct(UserRepository $repository)
    {
        $this->userRepo = $repository;

    }

    /**
     * @Get("/users", name="list_users")
     * @View(StatusCode = 200)
     * @param TagAwareCacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function users(TagAwareCacheInterface $cache)
    {
        return $cache->get('users', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $item->tag(['users']);
            $customer = $this->getUser();
            $usersOfCustomer = $this->userRepo->findBy(['customer' => $customer]);
            return $usersOfCustomer;
        });

    }


    /**
     * @Get("/users/{id}", name="one_user")
     * @View(StatusCode = 200)
     * @param User $user
     * @return User
     */
    public function getOneUser(User $user)
    {
        $requestedUser = $user;
        if ($this->getUser()->getId() == $requestedUser->getCustomer()->getId()) {
            return $user;
        } else {
            return new Response('Cet utilisateur ne vous appartient pas');
        }

    }

    /**
     * @Rest\Post("/users", name="create_user")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     *
     * @return User
     */
    public function createUser(User $user, EntityManagerInterface $entityManager)
    {
        $user->setCustomer($this->getUser());

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }


    /**
     * @Rest\Put("/users/{id}", name="update_user")
     * @View(StatusCode = 200)
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
     * @View(StatusCode = 200)
     * @param User $user
     * @param EntityManagerInterface $entityManager
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($user);
    }


}
