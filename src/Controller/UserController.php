<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\Paginate;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * Permet de gérer les requêtes sur les utilisateurs
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    private $userRepo;
    private $requestedUser;
    private $user;
    private $paginator;
    private $entityManager;
    private $validator;

    /**
     * UserController constructor.
     * @param UserRepository $repository
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(UserRepository $repository,
                                PaginatorInterface $paginator,
                                EntityManagerInterface $entityManager,
                                ValidatorInterface $validator)
    {
        $this->userRepo = $repository;
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }

    /**
     * @Get("/users", name="list_users")
     * @View(StatusCode = 200)
     * @param TagAwareCacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @Security("is_granted('ROLE_USER') ")
     */
    public function users(Request $request, TagAwareCacheInterface $cache)
    {
        $limitPerPage = 6; //number of user per page paginate

        $data = $cache->get('users', function (ItemInterface $item) {
            $item->expiresAfter(1800);
            $userManager = new UserManager();

            return $userManager->showAllUsers($this->getUser(), $this->userRepo);
        });

        $pagine = new Paginate($this->paginator, $data, $request);

        return $pagine->pagination($limitPerPage);
    }

    /**
     * @Get("/users/{id}", name="one_user")
     * @View(StatusCode = 200)
     * @Security("is_granted('ROLE_USER') ")
     */
    public function getOneUser($id, TagAwareCacheInterface $cache)
    {
        $this->requestedUser = $this->userRepo->findById($id);

        if (!$this->requestedUser) {
            return new JsonResponse(['message' => "Not registered user"], 404);
        }

        return $cache->get('users' . $this->requestedUser[0]->getId(), function (ItemInterface $item) {
            $item->expiresAfter(1800);
            $userManager = new UserManager();

            return $userManager->showUser($this->getUser()->getId(), $this->requestedUser);
        });
    }

    /**
     * @Rest\Post("/users", name="create_user")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     *
     * @return User|Response
     * @Security("is_granted('ROLE_USER') ")
     */
    public function createUser(User $user, TagAwareCacheInterface $cache)
    {
        $cache->delete('users');
        $this->user = $user;
        $userManager = new UserManager();

        return $userManager->createUser($this->getUser(), $this->user, $this->validator, $this->entityManager);
    }


    /**
     * @Rest\Put("/users/{id}", name="update_user")
     * @View(StatusCode = 200)
     * @ParamConverter("updatedUser", converter="fos_rest.request_body")
     * @param User $user
     * @param User $updatedUser //contains new data of the user's id
     * @param EntityManagerInterface $entityManager
     * @param TagAwareCacheInterface $cache
     * @return User|Response
     * @Security("is_granted('ROLE_USER') ")
     */
    public function updateUser($id, User $updatedUser,
                               EntityManagerInterface $entityManager,
                               TagAwareCacheInterface $cache)
    {
        $this->requestedUser = $this->userRepo->findById($id);

        if (!$this->requestedUser) {
            return new JsonResponse(['message' => "Not registered user"], 404);
        }
        $cache->delete('users' . $this->requestedUser[0]->getId());
        $updateUser = new UserManager();

        return $updateUser->updateUser($this->getUser(), $this->requestedUser, $updatedUser, $this->validator, $entityManager);
    }

    /**
     * @Rest\Delete("/users/{id}", name="delete_user")
     * @View(StatusCode = 204)
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @Security("is_granted('ROLE_USER') ")
     */
    public function deleteUser($id, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache)
    {
        $this->requestedUser = $this->userRepo->findById($id);
        if (!$this->requestedUser) {
            return new JsonResponse(['message' => "Not registered user"], 404);
        }
        $cache->delete('users' . $this->requestedUser[0]->getId());
        $updateUser = new UserManager();

        return $updateUser->deleteUser($this->getUser(), $this->requestedUser, $entityManager);
    }
}
