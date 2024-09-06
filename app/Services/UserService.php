<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\LocationNameRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class UserService
{
    protected $userRepository;
    protected $locationNameRepository;

    public function __construct(UserRepository $userRepository, LocationNameRepository $locationNameRepository)
    {
        $this->userRepository = $userRepository;
        $this->locationNameRepository = $locationNameRepository;

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

    public function getCountUsersAddedByManagers($id)
    {
        return $this->userRepository->getCountUsersAddedByManagers($id);
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

    public function getLocationNameCount()
    {
        return $this->locationNameRepository->getLocationNameCount();
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
