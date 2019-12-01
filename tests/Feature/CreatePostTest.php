<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_authenticated_can_upload_an_image_and_write_a_caption_for_make_a_new_post()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        storage::fake('public');
        $image = UploadedFile::fake()->image('test.jpg');

        $this->postJson('/' . $john->username . '/posts', [
            'image' => $image,
            'caption' => 'sed diam voluptua'
        ])->assertJson(['status' => 201]);

        $this->assertDatabaseHas('posts', [
            'image' => 'images/' . $image->hashName(),
            'caption' => 'sed diam voluptua'
        ]);
    }

    /** @test **/
    public function guest_can_not_create_new_a_post()
    {
        $john = create(User::class);

        $this->post('/' . $john->username . '/posts', [
            'image' => UploadedFile::fake()->image('test.jpg')
        ])->assertRedirect('/');
    }

    /** @test **/
    public function an_image_is_required_for_create_a_new_post()
    {
        $john = $this->signIn();

        $this->postJson('/' . $john->username . '/posts', [
            'image' => null,
            'caption' => 'sed diam voluptua'
        ])->assertJsonValidationErrors(['image']);
    }

    /** @test **/
    public function an_image_should_have_a_proper_format()
    {
        $john = $this->signIn();

        $this->postJson('/' . $john->username . '/posts', [
            'image' => UploadedFile::fake()->create('test.pdf'),
            'caption' => 'sed diam voluptua'
        ])->assertJsonValidationErrors(['image']);
    }

    /** @test **/
    public function users_can_see_their_posts_in_profile_page()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();
        $post = create(Post::class);

        $this->get('/' . $john->username . '/posts')->assertJson(['status' => 200]);
    }
}
