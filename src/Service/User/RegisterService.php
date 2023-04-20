<?php
namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService{


    /**
     * @var EntityManagerInterface
     */
    public EntityManagerInterface $manager;
    /**
     * @var UserPasswordHasherInterface
     */
    public UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $manager,UserPasswordHasherInterface $passwordHasher)
    {
        $this->manager = $manager;
        $this->passwordHasher = $passwordHasher;
    }

    public function register(User $user)
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword)
            ->setCreationDate(new \DateTimeImmutable())
            ->setRoles($user->getRoles());
        $this->manager->persist($user);
        $this->manager->flush();

    }
}