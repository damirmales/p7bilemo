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
        $customer = new Customer();
        $user = new User();

        for ($i = 0; $i < 6; $i++) {
            $product = new Product();

            $product->setName('phone_' . $i)
                ->setPrice(mt_rand(100, 300))
                ->setCreatedAt(new \DateTime());

            $manager->persist($product);

            //add product to Customer
            $customer->addProducts($product)
                ->setName('customer'.$i)
                ->setStatus(1)
                ->setPassword('motdepasse');

            //add Customer to User
            $user->setFirstName('bill_' . $i)
                ->setLastName('hobbes_' . $i)
                ->setEmail('email_' . $i . '@gemel.com')
                ->setCustomer($customer)
                ->setStatus(1)
                ->setPassword('pswd');
        }

        $manager->persist($customer);
        $manager->persist($user);
        $manager->flush();
    }
}
