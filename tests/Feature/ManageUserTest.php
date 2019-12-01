<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function users_can_update_their_username()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $this->patchJson('/settings/profiles/' . $john->username . '/username', [
            'username' => 'foobar'
        ])->assertRedirect('/settings/profiles/' . $john->fresh()->username);

        $this->assertEquals('foobar', $john->fresh()->username);
    }

    /** @test **/
    public function username_is_required_for_update()
    {
        $john = $this->signIn();

        $this->patchJson('/settings/profiles/' . $john->username . '/username', [
            'username' => null
        ])->assertJsonValidationErrors(['username']);
    }

    /** @test **/
    public function username_should_be_unique()
    {
        $john = $this->signIn();
        $david = create(User::class);

        $this->patchJson('/settings/profiles/' . $john->username . '/username', [
            'username' => $david->username
        ])->assertJsonValidationErrors(['username']);
    }

    /** @test **/
    public function a_username_can_only_contains_meaningful_words_and_numbers_and_underline()
    {
        $john = $this->signIn();

        $this->patchJson('/settings/profiles/' . $john->username . '/username', [
            'username' => 'Foo.Bar'
        ])->assertJsonValidationErrors(['username']);
    }

    /** @test **/
    public function username_can_get_updated_to_current_username_for_a_user()
    {
        $john = $this->signIn();

        $this->patchJson('/settings/profiles/' . $john->username . '/username', [
            'username' => $john->username
        ])->assertSessionDoesntHaveErrors(['username']);
    }
}
