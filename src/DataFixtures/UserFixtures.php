<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('admin@mail.co')
            ->setName('administrator')
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$wnG9133+whRz8uPYJUqejQ$/iaG5ZMpXJIn7dWS5CfjQd7VNHdYLX+1T1Mi9C48Fz4');

        $manager->persist($user);
        $manager->flush();

        $manager->flush();
    }
}
