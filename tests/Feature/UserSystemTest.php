<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserSystemTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_guest_can_create_an_account ()
    {
        $this->withoutExceptionHandling();

        $password = bcrypt('password');

        $user = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'username' => 'john_doe',
            'password' => $password,
            'password_confirmation' => $password
        ];

        $this->postJson('/register', $user)->assertJson(['status' => 201]);

        $this->assertDatabaseHas('users', [
            'name' => $user['name'],
            'email' => $user['email']
        ]);
    }

    /** @test **/
    public function name_and_email_and_username_and_password_is_required_for_create_account()
    {
        $this->postJson('/register', [
            'name' => null,
            'email' => null,
            'username' => null,
            'password' => null,
            'password_confirmation' => null
        ])->assertJsonValidationErrors(['name', 'email', 'username', 'password']);
    }

    /** @test **/
    public function a_username_can_only_contains_meaningful_words_and_numbers_and_underline()
    {
        $this->postJson('/check/username', [
            'username' => 'john.doe'
        ])->assertJsonValidationErrors(['username']);
    }

    /** @test **/
    public function username_should_be_unique()
    {
        $john = factory(User::class)->create(['name' => 'john']);

        $this->postJson('/check/username', [
            'username' => $john->username
        ])->assertJsonValidationErrors(['username']);
    }

    /** @test **/
    public function a_user_can_login_to_his_account_by_username()
    {
        $this->withoutExceptionHandling();

        $password = bcrypt('password');

        $user = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'username' => 'john_doe',
            'password' => $password,
            'password_confirmation' => $password
        ];

        $this->postJson('/register', $user)->assertJson(['status' => 201]);

        $userNew = [
            'username' => 'john_doe',
            'password' => $password,
        ];

        $this->postJson('/login', $userNew)->assertJson(['status' => 200, 'username' => $userNew['username']]);
    }

    /** @test **/
    public function a_user_can_login_to_his_account_by_email()
    {
        $this->withoutExceptionHandling();

        $password = bcrypt('password');

        $user = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'username' => 'john_doe',
            'password' => $password,
            'password_confirmation' => $password
        ];

        $this->postJson('/register', $user)->assertJson(['status' => 201]);

        $userNew = [
            'username' => 'john@doe.com',
            'password' => $password,
        ];

        $this->postJson('/login', $userNew)->assertJson(['status' => 200]);
    }

    /** @test **/
    public function username_or_email_and_password_is_required_for_login_to_account()
    {
        $userNew = [
            'username' => null,
            'password' => null,
        ];

        $this->postJson('/login', $userNew)->assertJsonValidationErrors(['username', 'password']);
    }

    /** @test **/
    public function user_can_logout_of_his_account()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $this->assertAuthenticated();

        $this->get('/logout')->assertRedirect('/');

        $this->assertNull(auth()->user());
    }
}
