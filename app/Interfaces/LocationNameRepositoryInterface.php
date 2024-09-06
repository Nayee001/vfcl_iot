<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface LocationNameRepositoryInterface
{
    public function create($location, $request);
    public function delete($id);
    public function getLocationNamesAsPerCustomer($customer_id);
}
