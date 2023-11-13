<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdatePass
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

    public function changePassword(mixed $data,User $user, $isAdmin = false )
    {
        if ($isAdmin){
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password_new']));
            $this->manager->persist($user);
            $this->manager->flush();
            return;
        }
        dd($this->passwordHasher->isPasswordValid($user, $data['password_old']),$user, $data);
        if($data['password_old'] && $this->passwordHasher->isPasswordValid($user, $data['password_old'])){
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password_new']));
        }
        $this->manager->persist($user);
        $this->manager->flush();
    }
}