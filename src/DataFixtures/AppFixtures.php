<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /*
    * @var UserPasswordHasherInterface
    */
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $manager): void
    {

        $users = [];

        $admin = new User();
        $hashedPassword = $this->hasher->hashPassword($admin, "admin");
        $admin->setRoles(['ROLE_ADMIN'])
            ->setPassword($hashedPassword)
            ->setEmail('admin@mail.com')
            ->setUsername('admin')
            ->setCreationDate(new \DateTime());
        $users[] = $admin;
        $manager->persist($admin);

        for ($i=1; $i<=5 ; $i++){
            $user = new User();
            $hashedPassword = $this->hasher->hashPassword($user, sprintf("user%d", $i));
            $user->setRoles(['ROLE_USER'])
                ->setPassword($hashedPassword)
                ->setEmail(sprintf("user%d", $i). "@mail.com")
                ->setUsername(sprintf("user%d", $i))
                ->setCreationDate(new \DateTime());
                
            $manager->persist($user);
            
            $users[] = $user;
        }

        $manager->flush();
    }
}
