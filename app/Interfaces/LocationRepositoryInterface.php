<?php

namespace App\Interfaces;

interface LocationRepositoryInterface
{
    public function create($user ,$input);
    public function update($id,$input);
}
