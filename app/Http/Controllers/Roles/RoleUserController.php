<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\ApiController;
use App\Models\Role;

class RoleUserController extends ApiController
{
    public function index(Role $role)
    {
        $role_users = $role->users;

        return $this->showAll($role_users);
    }
}
