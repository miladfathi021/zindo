<?php

namespace App\Http\Controllers;

use App\User;
use App\Zindo\Follow\FollowingStatusManager;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    /**
     *  Show users profile
     *
     * @param $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($user)
    {
        $user = User::where('username', $user)->firstOrFail();
        $posts = $user->posts()->latest()->get();

        $user['followCount'] = [
            'followings' => $user->followings()->where('status', FollowingStatusManager::STATUS_ACCEPTED)->count(),
            'followers' => $user->followers()->where('status', FollowingStatusManager::STATUS_ACCEPTED)->count(),
            'posts' => $posts->count()
        ];


        return view('profiles.index', compact(['user', 'posts']));
    }

    /**
     * show edit profile
     *
     * @param $username
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('view', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    /**
     *  a user can update name and biography
     *
     * @param $username
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('update', $user->profile);

        request()->validate([
            'name' => 'required|string|min:3|max:30',
            'bio' => 'max:90'
        ]);

        $user->update([
            'name' => request()->name
        ]);

        $user->profile()->update([
            'bio' => request()->bio
        ]);

        return back()->with('updated', 'information updated successfully');
    }
}
