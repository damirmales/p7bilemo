<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\CustomerProduct;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CustomerFixtures
 * @package App\DataFixtures
 */
class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 5; $i++) {
            $customer = new Customer();
            $customerProd = new CustomerProduct();
            $customerProd->addCustomer($customer);
            $userid = $this->getReference('userid'.$i);
            $customer->setName('customer' . $i)
                ->setUser($userid)
                ->setStatus(1)
                ->setPassword('toto');

            $manager->persist($customer);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            ProductFixtures::class
        );
    }
}
