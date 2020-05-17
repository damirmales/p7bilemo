<?php


namespace App\Manager;


class RegisterManager
{

    public function register($customer, $entityManager, $encoder)
    {
        $password = $customer->getPassword();
        $encoded = $encoder->encodePassword($customer, $password);
        $customer->setPassword($encoded);
        $entityManager->persist($customer);
        $entityManager->flush();

        return $customer;
    }

}