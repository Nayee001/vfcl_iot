<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
    public function getUserById($id)
    {
        return $this->userRepository->getUserById($id);
    }
    public function getManagersAndAdmin()
    {
        return $this->userRepository->getManagersAndAdmin();
    }

    public function getManagerAddedUsers($id)
    {
        return $this->userRepository->getUsersAddedByManagers($id);
    }

    public function getSelfManager($id)
    {
        return $this->userRepository->getUserById($id);
    }
    public function getManagerCount()
    {
        return $this->userRepository->getManagerCount();
    }
    public function getUserCount()
    {
        return $this->userRepository->getUserCount();
    }

    /**
     * Get All the users for admin
     *
     * @return Array
     */
    public function getOwners()
    {
        try {
            $transformedOwners = [];
            if (isSuperAdmin()) {
                $owners = $this->getManagersAndAdmin();
                foreach ($owners as $owner) {
                    $transformedOwners[$owner['id']] = $owner['fname'];
                }
            } else {
                $owners = $this->getSelfManager(Auth::user()->id);
                $transformedOwners[$owners->id] = $owners->fname;
            }
            return $transformedOwners;
        } catch (Exception $e) {
            Log::error("Error Getting Users: {$e->getMessage()}");
        }
    }
}
