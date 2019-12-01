<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use App\Zindo\User_Settings\ProfileStatusManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function after_registering_a_new_user_it_should_be_public_account()
    {
        $this->withoutExceptionHandling();

        $password = bcrypt('password');
        $user = [
            'name' => 'John Doe',
            'username' => 'john2w',
            'email' => 'john@doe.com',
            'password' => $password,
            'password_confirmation' => $password
        ];

        $this->postJson('/register', $user)->assertJson(['status' => 201]);

        $john = User::first();

        $this->assertDatabaseHas('settings', [
            'owner_id' => $john->id,
            'status' => ProfileStatusManager::STATUS_PUBLIC
        ]);
    }

    /** @test **/
    public function a_user_can_changes_his_profile_status_to_private()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $this->postJson('/settings/' . $john->username . '/status', [
            'status' => ProfileStatusManager::STATUS_PRIVATE
        ])->assertJson(['status' => 200]);

        $this->assertDatabaseHas('settings', [
            'status' => ProfileStatusManager::STATUS_PRIVATE
        ]);

    }
    /** @test **/
    public function a_user_can_changes_his_profile_status_to_public()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $this->postJson('/settings/' . $john->username . '/status', [
            'status' => ProfileStatusManager::STATUS_PRIVATE
        ])->assertJson(['status' => 200]);

        $this->assertDatabaseHas('settings', [
            'status' => ProfileStatusManager::STATUS_PRIVATE
        ]);

        $this->postJson('/settings/' . $john->username . '/status', [
            'status' => ProfileStatusManager::STATUS_PUBLIC
        ])->assertJson(['status' => 200]);

    }

    /** @test **/
    public function a_authenticated_user_cannot_see_other_users_posts_if_the_profile_is_private_and_did_not_follow_them()
    {
        $this->withoutExceptionHandling();

        $david = $this->signIn();

        $john = create(User::class);

        $post = create(Post::Class, ['owner_id' => $john->id]);

        $this->postJson('/settings/' . $john->username . '/status', [
            'status' => ProfileStatusManager::STATUS_PRIVATE
        ]);

        $this->get('/' . $john->username)->assertDontSee($post->image);
    }

    /** @test **/
    public function guest_cannot_see_other_users_posts_if_the_profile_is_private()
    {
        $this->withoutExceptionHandling();

        $john = create(User::class);

        $post = create(Post::Class, ['owner_id' => $john->id]);

        $john->setting->private();

        $this->get('/' . $john->username)->assertDontSee($post->image);
    }

    /** @test **/
    public function a_authenticated_user_can_see_other_users_posts_if_the_profile_is_private_and_follow_them()
    {
//        $this->withoutExceptionHandling();

        $david = $this->signIn();

        $john = create(User::class);

        $post = create(Post::Class, ['owner_id' => $john->id]);

        $this->postJson('/settings/' . $john->username . '/status', [
            'status' => ProfileStatusManager::STATUS_PRIVATE
        ]);

        $this->postJson('/' . $david->username . '/followings/' . $john->username)->assertJson(['status' => 201]);

        $john->accept($david);

        $this->get('/' . $john->username)->assertSee('</posts>');
    }
}
