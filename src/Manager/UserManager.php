<?php


namespace App\Manager;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserManager
{
    /**
     * @param $customer
     * @param $userRepo
     * @return mixed
     */
    public function showAllUsers($customer, $userRepo)
    {
        $custom = $customer;
        $usersOfCustomer = $userRepo->findBy(['customer' => $custom]);

        return $usersOfCustomer;
    }

    /**
     * @param $customerId
     * @param $user
     * @return JsonResponse
     */
    public function showUser($customerId, $user)
    {
        if ($customerId == $user[0]->getCustomer()->getId()) {
            return $user;
        }
        return new JsonResponse(['message' => 'This is not your user'], 401);
    }

    /**
     * @param $customer
     * @param $user
     * @param $validator
     * @param $entityManager
     * @return mixed
     */
    public function createUser($customer, $user, $validator, $entityManager)
    {
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            throw new HttpException(400, $errors);
        }
        $user->setCustomer($customer);
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    /**
     * @param $customer
     * @param $user
     * @param $updatedUser
     * @param $validator
     * @param $entityManager
     * @return mixed
     */
    public function updateUser($customer, $user, $updatedUser, $validator, $entityManager)
    {

        if ($customer->getId() == $user[0]->getCustomer()->getId()) {
            $errors = $validator->validate($user[0]);
            if (count($errors) > 0) {
                throw new HttpException(400, $errors);
            }
            $user[0]->setCustomer($customer)
                ->setFirstname($updatedUser->getFirstname())
                ->setLastname($updatedUser->getLastname())
                ->setEmail($updatedUser->getEmail());
            $entityManager->flush();

            return $user;
        }
        return new JsonResponse(['message' => 'This is not your user'], 401);
    }

    /**
     * @param $customer
     * @param $user
     * @param $entityManager
     * @return JsonResponse
     */
    public function deleteUser($customer, $user, $entityManager)
    {
        if ($customer->getId() == $user[0]->getCustomer()->getId()) {
            $entityManager->remove($user[0]);
            $entityManager->flush();
        } else {
            return new JsonResponse(['message' => 'This is not your user'],401);
        }
    }

}