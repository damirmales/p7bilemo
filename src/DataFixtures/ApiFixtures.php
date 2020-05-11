<?php

namespace App\DataFixtures;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class ApiFixtures
 * @package App\DataFixtures
 */
class ApiFixtures extends Fixture
{



    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {



        for ($i = 0; $i < 4; $i++) {
            $product = new Product();

            $product->setName('phone_' . $i)
                ->setPrice(mt_rand(100, 300))
                ->setDescription("Ce smartphone est équipé d'un écran 5,1 pouces Full HD, 
                du processeur quatre coeurs Snapdragon 801 à 2,5 Ghz de Qualcomm et d'un capteur photo 16 mégapixels")
                ->setCreatedAt(new \DateTime());

            $manager->persist($product);
        }
            for ($i = 0; $i < 2; $i++) {
                $customer = new Customer();
                //add product to Customer
                $customer->addProducts($product)
                    ->setName('customer' . $i)
                    ->setEmail('email_' . $i . '@customer.fr')
                    ->setRole('ROLE_USER')
                    ->setPassword('motdepasse');

                $manager->persist($customer);
            }

            for ($i = 0; $i < 3; $i++) {
                $user = new User();
                //add Customer to User
                $user->setFirstName('bill_' . $i)
                    ->setLastName('hobbes_' . $i)
                    ->setEmail('email_' . $i . '@gemel.com')
                    ->setCustomer($customer);

                $manager->persist($user);
            }

            $manager->flush();

    }
}
