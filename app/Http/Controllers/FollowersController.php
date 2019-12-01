<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FollowersController extends Controller
{
    /**
     * accept another user follower request.
     *
     * @param $authUser
     * @param $username
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store($authUser, $username)
    {
        if (auth()->user()->username == $username) {
            return redirect('/' . $authUser);
        }
        $user = User::where('username', $username)->first();

        auth()->user()->accept($user);

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * a user can decline a follow request.
     *
     * @param $authUser
     * @param $username
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($authUser, $username)
    {
        if (auth()->user()->username == $username) {
            return redirect('/' . $authUser);
        }

        $user = User::where('username', $username)->first();

        auth()->user()->decline($user);

        return response()->json([
            'status' => 200
        ]);
    }
}
