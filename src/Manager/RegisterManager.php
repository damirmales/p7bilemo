<?php


namespace App\Manager;


use Symfony\Component\HttpKernel\Exception\HttpException;

class RegisterManager
{

    /**
     * @param $customer
     * @param $entityManager
     * @param $encoder
     * @return mixed
     */
    public function register($customer, $entityManager, $encoder, $validator)
    {
        $errors = $validator->validate($customer);
        if (count($errors) > 0) {
            throw new HttpException(400, $errors);
        }
        $password = $customer->getPassword();
        $encoded = $encoder->encodePassword($customer, $password);
        $customer->setPassword($encoded);
        $entityManager->persist($customer);
        $entityManager->flush();

        return $customer;
    }
}