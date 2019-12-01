<?php


namespace App\Zindo\Follow;


use App\User;

trait Following
{

    /**
     *  User can have many followings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings()
    {
        return $this->belongsToMany(
            User::class,
            'followings',
            'follower',
            'following'
        );
    }

    /**
     * User can follow another user.
     *
     * @param $user
     */
    public function follow(User $user)
    {
        $this->followings()->attach($user, [
            'status' => FollowingStatusManager::STATUS_SUSPENDED
        ]);
    }

    /**
     * A user can cancel his follow request.
     *
     * @param $user
     */
    public function cancel(User $user)
    {
        $this->followings()->detach($user);
    }

    /**
     * a user can un fallow another user follower.
     *
     * @param User $user
     */
    public function unfollow(User $user)
    {
        $this->followings()->detach($user);
    }

    /**
     *check if a user has a following request from given user.
     *
     * @param User $user
     * @return bool
     */
    public function hasRequestedFollowing(User $user)
    {
        return $this->followings()
            ->where('following', $user->id)
            ->where('status', FollowingStatusManager::STATUS_SUSPENDED)
            ->exists();
    }

    /**
     * it can check if a user is following them.
     *
     * @param User $user
     * @return bool
     */
    public function isFollowing(User $user)
    {
        return $this->followings()
            ->where('following', $user->id)
            ->where('status', FollowingStatusManager::STATUS_ACCEPTED)
            ->exists();
    }
}
