<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        return $this->model::with('locations', 'locations.locationsNames')->find($id);
    }

    public function getUsersAddedByManagers($userId)
    {
        return $this->model::where('created_by', $userId)->pluck('fname', 'id');
    }

    public function store($inputData, $request)
    {
        $user = $this->model::create($inputData);
        return $user->assignRole($request->input('role'));
    }

    public function update($id, $validatedData)
    {
        $fillableFields = [
            'fname', 'lname', 'title', 'email', 'phonenumber', 'address', 'address_optional', 'city', 'state', 'country', 'postal_code'
        ];
        // Add 'password' to the fillable fields if it's present in the validated data
        if (array_key_exists('password', $validatedData)) {
            $fillableFields[] = 'password';
        }
        // Extract only the fillable fields from the validated data
        $modifiedData = array_intersect_key($validatedData, array_flip($fillableFields));
        // Update the device record
        $device = $this->model->findOrFail($id);
        return $device->update($modifiedData);
    }
}
