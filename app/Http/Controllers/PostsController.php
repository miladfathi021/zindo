<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Zindo\Follow\FollowingStatusManager;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->firstorfail();

        $posts = $user->posts()->latest()->paginate(12);

        if (request()->wantsJson()) {
            return response()->json([
                'posts' => $posts,
                'status' => 200
            ]);
        }

        return redirect('/'. $username);
    }

    public function show($username, Post $post)
    {
        $user = User::where('username', $username)->firstOrFail();

        if ($user->setting->isPrivate() && auth()->guest() || !$user->isFollower(auth()->user()) && auth()->id() != $user->id) {
            return redirect('/' . $user->username);
        }

        $posts = $user->posts()->latest()->get();
        $user['followCount'] = [
            'followings' => $user->followings()->where('status', FollowingStatusManager::STATUS_ACCEPTED)->count(),
            'followers' => $user->followers()->where('status', FollowingStatusManager::STATUS_ACCEPTED)->count(),
            'posts' => $posts->count()
        ];

        return view('posts.show', compact(['user', 'post']));
    }

    public function store($username)
    {
        $user = User::where('username', $username)->firstorfail();

        if(auth()->id() !== $user->id) {
            return redirect('/' . $username);
        }

        request()->validate([
            'image' => 'required|image|file|mimes:jpeg,png,gif',
            'caption' => 'max:2000'
        ]);

        $imagePath = request()->file('image')->hashName();

        request()->file('image')->storeAs('/images/', $imagePath, 'public');

        $post = $user->createPost([
            'image' => 'images/' . $imagePath,
            'caption' => request()->caption
        ]);

        return response()->json([
            'post' => $post,
            'status' => 201
        ]);
    }
}
