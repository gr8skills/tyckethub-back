<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verify']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_phone' => ['required'],
            'password' => ['required']
        ]);

        $credentials = Arr::only($request->all(), ['email_phone', 'password']);
        $user = User::where('email', $credentials['email_phone'])
                    ->orWhere('phone', $credentials['email_phone'])
                    ->first();
        if ($user) {
            $user->role = $user->roles()->first()->name ?? 'attendee';
        }

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }
        $user->tokens()->delete();
        $token = $user->createToken('auth')->plainTextToken;

        $payload['user'] = $user;
        $payload['token'] = $token;

        return $this->showUserToken((object)$payload);
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return $this->showMessage('No active session.');
        }
        \auth()->user()->tokens()->delete();
//        $user = User::find($request->get('user_id'));
//
//        if (!$user) {
//            return $this->showMessage('No active session.');
//
//        }
//        $user->tokens()->delete();
        return $this->showMessage('Successfully logged out.');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'min:11', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['required']
        ]);

        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
        $data['password'] = Hash::make($data['password']);
        unset($data['first_name']);
        unset($data['last_name']);
        $data['uid'] = User::generateUId();
        $data['verification_token'] = User::generateVerificationToken();

        $user = User::create($data);

        if (!$user) {
            return $this->errorResponse('Registration failed. Please try again.');
        }
        $user->roles()->sync($data['role']);
        $token = $user->createToken('auth')->plainTextToken;
        $user->role = $user->roles->first()->name;

        $payload['user'] = $user;
        $payload['token'] = $token;

        return $this->showUserToken((object)$payload);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();
        if (!$user) {
            return $this->errorResponse('Token does not exist or has expired. Please use the resend validation link to restart the process.');
        }

        if (!is_null($user->email_verified_at)) {
            if (!is_null($user->verification_token)) {
                $user->verification_token = null;
                $user->save();
            }
            return $this->showMessage('Your account has already been verified.');
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return $this->showMessage('Verification successful');
    }

    public function resendVerificationToken(Request $request)
    {
        $user = $request->user();
        $user->verification_token = User::generateVerificationToken();
        $user->save();

        //TODO Send the verification link to the user's email
    }
}
