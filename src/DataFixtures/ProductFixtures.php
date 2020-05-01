<?php

namespace App\DataFixtures;

use App\Entity\CustomerProduct;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ProductFixtures
 * @package App\DataFixtures
 */
class ProductFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();

            $customer = new Customer();
            $customer->addProduct($product);

            $product->setName('phone_' . $i)
                ->setPrice(mt_rand(100, 300))
                ->setCreatedAt(new \DateTime());

            $manager->persist($product);
        }
        $manager->flush();
    }

}
