<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;

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
        return User::get();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }
    public function getUsersAddedByManagers($userId){
        return $this->model::where('created_by',$userId)->get();
    }

    public function store($inputData,$request)
    {
        $user = $this->model::create($inputData);
        return $user->assignRole($request->input('role'));
    }
}
