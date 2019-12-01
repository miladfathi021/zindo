<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_view_the_photo_and_caption_by_clicking_on_another_users_post()
    {
        $this->withoutExceptionHandling();

        $john = create(User::class);
        $post = create(Post::class);

        $this->get('/' . $john->username . '/posts/' . $post->id)->assertSee($post->image)->assertSee($post->caption);
    }
}
