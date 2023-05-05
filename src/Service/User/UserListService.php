<?php
namespace App\Service\User;

use App\Repository\UserRepository;

class UserListService{


    /**
     * @var UserRepository
     */
    public UserRepository $repos;

    public function __construct(UserRepository $repos)
    {
        $this->repos = $repos;
    }

    public function list()
    {
        return $this->repos->findAll();
    }
}