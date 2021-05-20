<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\ApiController;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RoleController extends ApiController
{
    public function index()
    {
        $roles = Role::all('id', 'name');
        return $this->showAll($roles);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'min:3']
        ];

        $validate_data = $request->validate($rules);

        $role = Role::create($validate_data);

        if ($role) {
            return $this->show($role);
        }
        return $this->errorResponse('Operation failed. Please try again.');
    }

    public function show(Role $role)
    {
        return $this->showOne($role);
    }

    public function update(Request $request, Role $role)
    {
        $data = Arr::only($request->all(), ['name', 'description']);
        $role->fill($data);
        if ($role->isDirty()) {
            $role->save();
            return $this->showOne($role);
        }
        return $this->errorResponse('Role not updated. None of the role attributes were modified.');
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return $this->showOne($role);
        } catch (\Exception $e) {
            return $this->errorResponse('Operation failed. Please try again later');
        }
    }
}
