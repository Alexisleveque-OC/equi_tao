<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateService
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

    public function update(array $data, User $user)

    {
        if($data['password_old'] && $this->passwordHasher->isPasswordValid($user, $data['password_old'])){
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password_new']));
        }
        $user->setUsername($data['username'])
            ->setEmail($data['email']);


        $this->manager->persist($user);
        $this->manager->flush();

    }
}