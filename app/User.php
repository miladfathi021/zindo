<?php

namespace App;

use App\Zindo\Follow\Follower;
use App\Zindo\Follow\Following;
use App\Zindo\Follow\FollowingStatusManager;
use App\Zindo\User_Settings\ProfileStatusManager;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, Following, Follower;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            Profile::create([
                'owner_id' => $user->id
            ]);
            Setting::create([
                'owner_id' => $user->id,
                'status' => ProfileStatusManager::STATUS_PUBLIC
            ]);
        });
    }

    /**
     * A user have one profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'owner_id');
    }

    /**
     * Add an avatar path to user table.
     *
     * @param $avatar
     */
    public function addAvatar($avatar)
    {
        $this->profile()->update([
            'avatar' => 'avatars/' . $avatar
        ]);
    }

    /**
     * A user can have many posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts ()
    {
        return $this->hasMany(Post::class, 'owner_id');
    }

    /**
     * A user have one settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function setting()
    {
        return $this->hasOne(Setting::class, 'owner_id');
    }

    /**
     * A user can create a new post.
     *
     * @param $post
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createPost($post)
    {
        return $this->posts()->create($post);
    }
}
