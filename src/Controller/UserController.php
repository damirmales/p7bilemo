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
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;


class UserController extends AbstractController
{
    private $userRepo;

    /**
     * @return mixed
     */
    public function getUserRepo()
    {
        return $this->userRepo;
    }

    /**
     * @param $userRepo
     */
    public function setUserRepo($userRepo): self
    {
        $this->userRepo = $userRepo;
        return $this;
    }


    /**
     * @Get("/users", name="list_users")
     * @View(StatusCode = 200)
     * @param UserRepository $userRepository
     * @param TagAwareCacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function users(UserRepository $userRepository, TagAwareCacheInterface  $cache)
    {
        $this->setUserRepo($userRepository);

        return $cache->get('users', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $item->tag(['users']);
            return $this->getUserRepo()->findAll();
        });

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
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param CustomerRepository $customerRepository
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
