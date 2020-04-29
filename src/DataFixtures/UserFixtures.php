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
    public $userid = [];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $this->userid[$i] = $user;
            $user->setFirstName('bill_' . $i)
                ->setLastName('hobbes_' . $i)
                ->setEmail('email_' . $i . '@gemel.com')
                ->setCustomer('custom' . $i)
                ->setStatus(1)
                ->setPassword('toto');
            $this->setReference('userid'.$i, $this->userid[$i]);
            $manager->persist($user);
        }
        $manager->flush();

    }

}
