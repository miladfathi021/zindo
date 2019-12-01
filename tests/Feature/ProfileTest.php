<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_authenticated_can_have_a_his_profile()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn(factory(User::class)->create(['name' => 'john', 'username' => 'johndoe']));

        $this->get('/' . $john->username)->assertSee($john->name);
    }

    /** @test **/
    public function a_user_authenticated_can_have_edit_profile_page()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn(factory(User::class)->create(['name' => 'john', 'username' => 'johndoe']));

        $this->get('/settings/profiles/' . $john->username)->assertSee($john->name);
    }

    /** @test **/
    public function a_user_athenticated_can_update_name_and_biography()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn(factory(User::class)->create(['name' => 'john', 'username' => 'johndoe']));

        $this->patch('/settings/profiles/' . $john->username . '/update', [
            'name' => 'John Doe',
            'bio' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, possimus tempora.'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe'
        ]);


        $this->assertDatabaseHas('profiles', [
            'bio' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, possimus tempora.'
        ]);
    }

    /** @test **/
    public function bio_may_not_be_greater_than_90_characters()
    {
        $john = $this->signIn(factory(User::class)->create(['name' => 'john', 'username' => 'johndoe']));

        $this->patch('/settings/profiles/' . $john->username . '/update', [
            'bio' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, possimus tempora test.'
        ])->assertSessionHasErrors(['bio']);
    }

    /** @test **/
    public function name_is_required_for_update_profile()
    {
        $john = $this->signIn(factory(User::class)->create(['name' => 'john', 'username' => 'johndoe']));

        $this->patch('/settings/profiles/' . $john->username . '/update', [
            'name' => null
        ])->assertSessionHasErrors(['name']);
    }
}
