<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserRoleController extends ApiController
{
    public function index(User $user)
    {
        $roles = $user->roles;
        return $this->showAll($roles);
    }

    public function store(Request $request, User $user) {
        if ($user->roles->count() > 0) {
            $user->roles->each(function ($role) use (&$user){
                $user->roles()->detach($role->id);
            });
        }
        $user->roles()->attach($request->role_id);
        $role = Role::find($request->role_id);
        return $this->showOne($role);
    }

}
