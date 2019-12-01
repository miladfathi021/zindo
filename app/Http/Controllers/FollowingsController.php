<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FollowingsController extends Controller
{
    /**
     *  A user can follow another user.
     *
     * @param $user
     * @param $username
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store($authUser, $username)
    {
        if (auth()->user()->username == $username) {
            return redirect('/' . $username);
        }

        $user = User::where('username', $username)->first();

        auth()->user()->follow($user);

        return response()->json([
            'status' => 201
        ]);
    }


    /**
     * A user can cancel his follow request.
     *
     * @param $authUser
     * @param $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($authUser, $username)
    {
        if (auth()->user()->username == $username) {
            return redirect('/' . $username);
        }

        $user = User::where('username', $username)->first();

        auth()->user()->cancel($user);

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * a user can un fallow another user follower.
     *
     * @param $authUser
     * @param $username
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($authUser, $username)
    {
        if (auth()->user()->username == $username) {
            return redirect('/' . $username);
        }

        $user = User::where('username', $username)->first();

        auth()->user()->unfollow($user);

        return response()->json([
            'status' => 200
        ]);
    }
}
