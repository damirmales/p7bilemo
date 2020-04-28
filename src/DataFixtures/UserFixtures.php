<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstName('phone_' . $i)
                ->setLastName('custiomer' . $i)
                ->setEmail('phone_' . $i)
                ->setCustomer('custom' . $i)
                ->setStatus(1)
                ->setPassword('toto');

            $manager->persist($user);
        }
        $manager->flush();
    }

}
