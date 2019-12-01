<?php

namespace Tests\Unit;

use App\Post;
use App\Profile;
use App\User;
use App\Zindo\Follow\FollowingStatusManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_have_many_followings()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn(create(User::class, ['name' => 'John', 'username' => 'john']));
        $david = create(User::class, ['name' => 'David', 'username' => 'david']);

        $john->follow($david);

        $this->assertInstanceOf(Collection::class, $john->followings);
    }

    /** @test **/
    public function a_user_can_have_many_followers()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn(create(User::class, ['name' => 'John', 'username' => 'john']));
        $david = create(User::class, ['name' => 'David', 'username' => 'david']);

        $david->follow($john);

        $this->assertInstanceOf(Collection::class, $john->followers);
    }

    /** @test **/
    public function it_can_follow_other_users()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn(create(User::class, ['name' => 'John', 'username' => 'john']));
        $david = create(User::class, ['name' => 'David', 'username' => 'david']);

        $john->follow($david);

        $this->assertTrue($john->followings->contains($david));

        $this->assertDatabaseHas('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_SUSPENDED
        ]);
    }

    /** @test **/
    public function it_can_cancel_follow_request()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();
        $david = create(User::class);

        $john->follow($david);

        $this->assertDatabaseHas('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_SUSPENDED
        ]);

        $john->cancel($david);

        $this->assertDatabaseMissing('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_SUSPENDED
        ]);
    }

    /** @test **/
    public function it_can_check_if_user_following_request()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();
        $david = create(User::class);

        $john->follow($david);

        $this->assertTrue($john->hasRequestedFollowing($david));
    }

    /** @test **/
    public function it_can_check_if_user_is_follower_request()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();
        $david = create(User::class);

        $john->follow($david);

        $this->assertTrue($david->hasRequestedFollower($john));
    }

    /** @test **/
    public function it_can_decline_a_follower_request()
    {
        $this->withoutExceptionHandling();

        $john = create(User::class);
        $david = $this->signIn();

        $john->follow($david);

        $david->decline($john);

        $this->assertDatabaseHas('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_DECLINED
        ]);
    }

    /** @test **/
    public function it_can_accept_a_follower_request()
    {
        $this->withoutExceptionHandling();

        $john = create(User::class);
        $david = $this->signIn();

        $john->follow($david);

        $david->accept($john);

        $this->assertDatabaseHas('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_ACCEPTED
        ]);
    }

    /** @test **/
    public function it_can_check_if_a_user_is_following_them()
    {
        $this->withoutExceptionHandling();

        $john = create(User::class);
        $david = $this->signIn();

        $john->follow($david);

        $david->accept($john);

        $this->assertTrue($john->isFollowing($david));
    }

    /** @test **/
    public function it_can_check_if_a_user_is_follower_them()
    {
        $this->withoutExceptionHandling();

        $john = create(User::class);
        $david = $this->signIn();

        $john->follow($david);

        $david->accept($john);

        $this->assertTrue($david->isFollower($john));
    }

    /** @test **/
    public function it_can_unfollow_another_user()
    {
        $this->withoutExceptionHandling();

        $john = create(User::class);
        $david = $this->signIn();

        $john->follow($david);

        $david->accept($john);

        $john->unfollow($david);

        $this->assertDatabaseMissing('followings', [
            'follower' => $john->id,
            'following' => $david->id
        ]);
    }

    /** @test **/
    public function a_user_can_add_avatar()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $filename = 'avatar.test';

        $john->addAvatar($filename);

        $this->assertDatabaseHas('profiles', [
            'avatar' => 'avatars/' . $filename
        ]);
    }

    /** @test **/
    public function a_user_can_have_many_posts()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $posts = create(Post::class);

        $this->assertInstanceOf(Collection::class, $john->posts);
    }

    /** @test **/
    public function a_user_can_make_a_post()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $post = [
            'image' => 'images/test.jpg',
            'caption' => 'test'
        ];

        $john->createPost($post);

        $this->assertDatabaseHas('posts', [
            'image' => 'images/test.jpg',
            'caption' => 'test'
        ]);
    }
}
