<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN'])
            ->setPassword('tata')
            ->setEmail('tata@mail.com')
            ->setUsername('tata')
            ->setCreationDate(new \DateTime());

         $manager->persist($user);

        $manager->flush();
    }
}

