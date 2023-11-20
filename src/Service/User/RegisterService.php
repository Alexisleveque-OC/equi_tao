<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Service\Attribute\Required;

class RegisterService
{
	#[Required]
    public EntityManagerInterface $manager;

    #[Required]
    public UserPasswordHasherInterface $passwordHasher;

    public function register(User $user, bool $editMode = null)

    {
        if (!$user->getRoles()) {
            $user->setRoles(['ROLE_USER']);
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $user->setRoles(['ROLE_ADMIN']);
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());

        $user->setUsername($user->getUsername())
            ->setEmail($user->getEmail());

        if (!$editMode) {
            $user->setCreationDate(new \DateTimeImmutable())
                ->setPassword($hashedPassword);
        }

        $this->manager->persist($user);
        $this->manager->flush();

    }
}