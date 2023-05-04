<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setRoles(['ROLE_ADMIN'])
            ->setPassword('admin')
            ->setEmail('admin@mail.com')
            ->setUsername('admin')
            ->setCreationDate(new \DateTime());

        $manager->persist($admin);

        for ($i=0; $i<5 ; $i++);{
            $user = new User();
            $user->setPassword("user". $i)
                ->setEmail("user". $i . "@mail.com")
                ->setUsername("user". $i)
                ->setCreationDate(new \DateTime());
           
            $manager->persist($user);
        }

        $manager->flush();
    }
}
