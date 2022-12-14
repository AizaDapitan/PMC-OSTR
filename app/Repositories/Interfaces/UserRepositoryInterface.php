<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function all();

    public function create($fields);

    public function update($fields, $id);
    
    public function getById($id);

    public function GetRoleName($id);
}