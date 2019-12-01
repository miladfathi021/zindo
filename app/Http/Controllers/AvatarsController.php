<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AvatarsController extends Controller
{
    public function store($username)
    {
        $user = User::where('username', $username)->firstorfail();

        $this->authorize('update', $user->profile);

        request()->validate([
            'avatar' => 'required|image|file|mimes:jpeg,png,gif'
        ]);

        $avatarName = request()->file('avatar')->hashName();

        request()->file('avatar')->storeAs('/avatars/', $avatarName, 'public');

        $user->addAvatar($avatarName);

        return response()->json([
            'userProfile' => $user->fresh()->profile,
            'status' => 201
        ]);
    }
}
