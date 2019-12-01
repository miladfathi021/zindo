<?php

namespace Tests\Feature;

use App\User;
use App\Zindo\Follow\FollowingStatusManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FollowFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_authenticated_can_follow_another_user()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn(create(User::class, ['name' => 'John', 'username' => 'john']));
        $david = create(User::class, ['name' => 'David', 'username' => 'david']);

        $this->postJson('/' . $john->username . '/followings/' . $david->username)->assertJson(['status' => 201]);

        $this->assertDatabaseHas('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_SUSPENDED,
        ]);
    }

    /** @test **/
    public function a_user_can_not_follow_themselves()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $this->postJson('/' . $john->username . '/followings/' . $john->username)->assertRedirect('/' . $john->username);

    }

    /** @test **/
    public function a_user_can_cancel_his_follow_request()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();
        $david = create(User::class);

        $this->postJson('/' . $john->username . '/followings/' . $david->username)->assertJson(['status' => 201]);

        $this->assertDatabaseHas('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_SUSPENDED
        ]);

        $this->postJson('/' . $john->username . '/followings/' . $david->username . '/cancel')->assertJson(['status' => 200]);

        $this->assertDatabaseMissing('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_SUSPENDED
        ]);
    }

    /** @test **/
    public function after_sending_a_follow_request_the_follower_may_decline_the_request()
    {
        $this->withoutExceptionHandling();

        $john = create(User::class);
        $david = $this->signIn();

        $john->follow($david);

        $this->postJson('/' . $david->username . '/followers/' . $john->username . '/decline')->assertJson(['status' => 200]);

        $this->assertDatabaseHas('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_DECLINED,
        ]);
    }

    /** @test **/
    public function a_user_can_accept_another_user_follower_request()
    {
        $this->withoutExceptionHandling();

        $john = create(User::class);
        $david = $this->signIn();

        $john->follow($david);

        $this->postJson('/' . $david->username . '/followers/' . $john->username . '/accept')->assertJson(['status' => 200]);

        $this->assertDatabaseHas('followings', [
            'follower' => $john->id,
            'following' => $david->id,
            'status' => FollowingStatusManager::STATUS_ACCEPTED,
        ]);
    }

    /** @test **/
    public function a_user_can_unfallow_another_user_follower()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();
        $david = create(User::class);

        $john->follow($david);

        $david->accept($john);

        $this->postJson('/' . $john->username . '/followings/' . $david->username . '/unfollow')->assertJson(['status' => 200]);

        $this->assertDatabaseMissing('followings', [
            'follower' => $john->id,
            'following' => $david->id,
        ]);
    }
}
