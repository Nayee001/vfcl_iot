<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Models\Location;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAllUsers()
    {
        return $this->model::get();
    }

    public function getManagersAndAdmin()
    {
        return $this->model::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Manager', 'Super admin']);
        })->get();
    }

    public function getUserById($id)
    {
        return $this->model::with('locations')->find($id);
    }

    public function getUsersAddedByManagers($userId)
    {
        return $this->model::where('created_by', $userId)->pluck('fname','id');
    }

    public function store($inputData, $request)
    {
        $user = $this->model::create($inputData);
        return $user->assignRole($request->input('role'));
    }

    public function getLocationTypes(){
        return Location::LOCATIONTYPE;
    }
}
