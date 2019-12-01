<?php


namespace App\Zindo\Follow;


use App\User;

trait Follower
{
    /**
     * A user may have many followers.
     *
     * @return mixed
     */
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'followings',
            'following',
            'follower'
        );
    }

    /**
     * check if a user has a follower request from given user.
     *
     * @param User $user
     * @return mixed
     */
    public function hasRequestedFollower(User $user)
    {
        return $this->followers()
            ->where('follower', $user->id)
            ->where('status', FollowingStatusManager::STATUS_SUSPENDED)
            ->exists();
    }

    /**
     * A user can decline a follow request.
     *
     * @param User $user
     */
    public function decline(User $user)
    {
        $this->followers()->sync([
            $user->id => [
                'status' => FollowingStatusManager::STATUS_DECLINED
            ]
        ]);
    }

    /**
     *  A user accept a follow request.
     *
     * @param User $user
     */
    public function accept(User $user)
    {
        $this->followers()->sync([
            $user->id => [
                'status' => FollowingStatusManager::STATUS_ACCEPTED
            ]
        ]);
    }

    /**
     * it can check if a user is follower them.
     *
     * @param User $user
     * @return mixed
     */
    public function isFollower(User $user)
    {
        return $this->followers()
            ->where('follower', $user->id)
            ->where('status', FollowingStatusManager::STATUS_ACCEPTED)
            ->exists();
    }
}
