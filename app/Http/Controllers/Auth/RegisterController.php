<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * A guest can create his account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $user = request()->validate([
            'name' => 'required|min:3|max:30|string',
            'email' => 'required|email|max:255|string|unique:users,email',
            'username' => 'required|string|min:5|max:32|regex:/^[A-Za-z0-9]+(?:[_][A-Za-z0-9]+)*$/|unique:users,username',
            'password' => 'required|string|min:6|max:255|confirmed'
        ]);

        $user['password'] = bcrypt($user['password']);

        User::create($user);

        return response()->json([
            'message' => 'Registration successfully completed.',
            'status' => 201
        ]);
    }
}
