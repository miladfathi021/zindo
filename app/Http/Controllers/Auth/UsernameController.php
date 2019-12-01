<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UsernameController extends Controller
{
    /**
     * check username field in register form.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function check()
    {
        request()->validate([
            'username' => 'required|string|min:5|max:32|regex:/^[A-Za-z0-9]+(?:[_][A-Za-z0-9]+)*$/|unique:users,username'
        ]);

        return response()->json([
            'message' => request()->username . ' is available.',
            'status' => 200
        ]);
    }

    public function update($username)
    {

//        dd(\request()->all());
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('update', $user->profile);

        $attribute = request()->validate([
            'username' => 'required|string|min:5|max:32|regex:/^[A-Za-z0-9]+(?:[_][A-Za-z0-9]+)*$/|unique:users,username,' . $user->id,
        ]);

        auth()->user()->update($attribute);

        return redirect('/settings/profiles/' . $attribute['username'])->with('updated', 'information updated successfully');
    }
}
