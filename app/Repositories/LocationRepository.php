<?php

namespace App\Repositories;

use App\Interfaces\LocationRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

class LocationRepository implements LocationRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create($users, $input)
    {
        $inputData = [
            'user_id' => $users['id'],
            'address' => $input['address'],
            'address_optional' => $input['address_optional'],
            'city' => $input['city'],
            'state' => $input['state'],
            'country' => $input['country'],
            'postal_code' => $input['postal_code'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $userLocation = $this->model::create($inputData);
        return $userLocation;
    }

    public function update($id, $input)
    {
        $inputData = [
            'address'       => $input['address'],
            'address_optional'       => $input['address_optional'],
            'city'          => $input['city'],
            'state'         => $input['state'],
            'country'       => $input['country'],
            'postal_code'   => $input['postal_code'],
        ];

        $userLocation = $this->model::updateOrCreate(
            ['user_id' => $id],
            $inputData
        );

        return $userLocation;
    }
}
