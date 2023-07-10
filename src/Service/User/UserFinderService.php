<?php
namespace App\Service\User;

use App\Repository\UserRepository;

class UserFinderService{


    /**
     * @var UserRepository
     */
    public UserRepository $repos;

    public function __construct(UserRepository $repos)
    {
        $this->repos = $repos;
    }

    public function listAll()
    {
        return $this->repos->findAll();
    }

    public function findOneUser(int $id)
    {
        return $this->repos->findOneById($id);
    }
}