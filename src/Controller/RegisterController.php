<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Manager\RegisterManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @param Customer $customer
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     * @return mixed
     */
    public function register(Customer $customer,
                             EntityManagerInterface $entityManager,
                             UserPasswordEncoderInterface $encoder,
                             ValidatorInterface $validator)
    {
        $registerManager = new RegisterManager();

        return $registerManager->register($customer,$entityManager, $encoder, $validator);
    }
}
