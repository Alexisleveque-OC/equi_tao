<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UpdateRoleService
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function updateRole(User $user)
    {

        if (!$user->getRoles() || $user->getRoles()['1'] != null) {
            $user->setRoles(['ROLE_USER']);
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())){
            $user->setRoles(['ROLE_ADMIN']);
        }
        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}