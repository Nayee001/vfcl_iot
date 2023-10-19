<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface DeviceTypeRepositoryInterface
{
    public function getAllUsers();
    public function getUserById($id);
    public function store($input);
    public function update($request,$id);
    public function destroy($id);
    public function dataTable($request);
}
