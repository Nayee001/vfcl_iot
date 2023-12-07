<?php

namespace App\Repositories;

use App\Interfaces\DeviceDataRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class DeviceDataRepository implements DeviceDataRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

}
