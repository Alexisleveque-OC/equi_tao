<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService
{


    public EntityManagerInterface $manager;
    /**
     * @var UserPasswordHasherInterface
     */
    public UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->manager = $manager;
        $this->passwordHasher = $passwordHasher;
    }

    public function register(User $user)

    {
        if (!$user->getRoles()) {
            $user->setRoles(['ROLE_USER']);
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())){
            $user->setRoles(['ROLE_ADMIN']);
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());

        $user->setCreationDate(new \DateTimeImmutable())
            ->setPassword($hashedPassword);

        $this->manager->persist($user);
        $this->manager->flush();

    }
}