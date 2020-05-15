<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Permet de gérer l'enregistrement des clients
 * Class AuthController
 * @package App\Controller
 */
class RegisterController extends AbstractController
{
    /**
     * @Rest\Post("/register", name="auth")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("customer", converter="fos_rest.request_body")
     */
    public function register(Customer $customer, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encoder)
    {
        $password = $customer->getPassword();
        $encoded = $encoder->encodePassword($customer, $password);
        $customer->setPassword($encoded);
        $entityManager->persist($customer);
        $entityManager->flush();

        return $customer;
    }
}