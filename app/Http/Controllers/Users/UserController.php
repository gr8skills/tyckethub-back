<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
    }

    public function store(Request $request)
    {
        //Form input validation
        $rules = [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'min:11', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];

        $request->validate($rules);

        //Create user after validation pass
        $user = User::create([
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password'))
        ]);

        if ($user)
            return $this->showOne($user, 200);
        else
            return $this->errorResponse('Sorry operation failed. Please try again later.');
    }


    public function show(User $user)
    {
        return $this->showOne($user);
    }

    public function update(Request $request, User $user)
    {
        $modified_user_data = Arr::except($request->all(), ['email', 'phone']);
        $user->update($modified_user_data);
        return $this->showOne($user);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return $this->showOne($user);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

    }
}
