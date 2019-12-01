<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_authenticated_can_upload_an_avatar()
    {
        $this->withoutExceptionHandling();

        Storage::fake('public');
        $image = UploadedFile::fake()->image('avatar.jpg');

        $john = $this->signIn();

        $this->postJson('/settings/profiles/' . $john->username . '/avatar', [
            'avatar' => $image
        ])->assertJson(['status' => 201]);

        $this->assertDatabaseHas('profiles', [
            'avatar' => 'avatars/' . $image->hashName()
        ]);
    }

    /** @test **/
    public function an_avatar_should_be_a_valid_image()
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->create('avatar.pdf');

        $john = $this->signIn();

        $this->postJson('/settings/profiles/' . $john->username . '/avatar', [
            'avatar' => $image
        ])->assertJsonValidationErrors(['avatar']);
    }

    /** @test **/
    public function avatar_is_required()
    {
        $john = $this->signIn();

        $this->postJson('/settings/profiles/' . $john->username . '/avatar', [
            'avatar' => null
        ])->assertJsonValidationErrors(['avatar']);
    }
}
