<?php

use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);

    $this->post = Post::factory()->create(['author_id' => $this->user->id]);
});

test('can get all posts for authenticated user', function () {
    $response = $this->getJson('/api/v1/posts');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'show all posts'
        ]);
});

test('requires authentication to get all posts', function () {
    $response = $this->getJson('/api/v1/posts');

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
});

test('can create a new post for authenticated user', function () {
    $postData = [
        'title' => 'Test Post',
        'content' => 'This is a test post content',
        'author_id' => $this->user->id
    ];

    $response = $this->postJson('/api/v1/posts/store', $postData);

    $response->assertCreated()
        ->assertJson([
            'message' => 'new post created',
            'post' => [
                'title' => 'Test Post',
                'content' => 'This is a test post content',
                'author_id' => $this->user->id
            ]
        ]);

    $this->assertDatabaseHas('posts', $postData);
});

test('can create a new post for authenticated user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $postData = [
        'title' => 'Test Post',
        'content' => 'This is a test post content',
        'author_id' => $user->id
    ];

    $response = $this->postJson('/api/v1/posts/store', $postData);
    $response->assertCreated();


    $this->assertDatabaseHas('posts', $postData);
});

test('can show a post for authenticated user', function () {
    $response = $this->getJson("/api/v1/posts/show/{$this->post->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'show post',
        ]);
});

test('can update a post for authenticated user', function () {
    $updatedData = [
        'title' => 'Updated Title',
        'content' => 'Updated content'
    ];

    $response = $this->putJson("/api/v1/posts/update/{$this->post->id}", $updatedData);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'post updated',
        ]);

    $this->assertDatabaseHas('posts', array_merge(['id' => $this->post->id], $updatedData));
});

test('can delete a post for authenticated user', function () {
    $response = $this->deleteJson("/api/v1/posts/destroy/{$this->post->id}");

    $response->assertStatus(204);
    $this->assertSoftDeleted($this->post);
});
