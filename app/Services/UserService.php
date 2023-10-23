<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function getManagerAddedUsers($id)
    {
        return $this->userRepository->getUsersAddedByManagers($id);
    }

    /**
     * Get All the users for admin
     *
     * @return Array
     */
    public function getOwners()
    {
        try {
            if (isSuperAdmin()) {
                return $this->getAllUsers();
            } else {
                return $this->getManagerAddedUsers(Auth::user()->id);
            }
        } catch (Exception $e) {
        }
    }
}
