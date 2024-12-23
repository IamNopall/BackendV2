<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
            'category' => 'Technology',
            'status' => 'published',
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
        ]);
    }

    public function test_read_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_update_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $response = $this->put('/posts/' . $post->id, [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'category' => 'Health',
            'status' => 'archived',
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', [
            'title' => 'Updated Title',
        ]);
    }

    public function test_delete_post()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $post = Post::factory()->create();

    $response = $this->delete('/posts/' . $post->id);

    $response->assertRedirect('/posts');
    $this->assertDatabaseMissing('posts', ['id' => $post->id]); // Periksa apakah entri sudah tidak ada
}

}
