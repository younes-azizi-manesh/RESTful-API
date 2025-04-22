<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('test new user can register', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'younes',
        'email' => 'younesazizimanesh@gmail.com',
        'password' => '12348765',
        'password_confirmation' => '12348765'
    ]);
    $response->assertCreated()
        ->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'email'],
            'token'
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'younesazizimanesh@gmail.com'
    ]);
});

test('validates registration data', function () {
    $response = $this->postJson('/api/register', [
        'name' => '',
        'email' => 'invalid-email',
        'password' => 'short',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

test('can login with valid credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'younesazizimanesh@gmail.com',
        'password' => '12348765'
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'message',
            'user',
            'token'
        ]);
});

test('fails to login with invalid credentials', function () {
    $user = User::factory()->create([
        'name' => 'younes',
        'email' => 'younesazizimanesh@gmail.com',
        'password' => '12345678',
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'john@example.com',
        'password' => '11111111'
    ]);

    $response->assertUnauthorized()
        ->assertJson(['message' => 'Logged in failed!']);
});

test('can logout authenticated user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/logout');

    $response->assertOk()
        ->assertJson(['message' => 'logged out successfully']);
});

test('prevents logout for unauthenticated users', function () {
    $response = $this->postJson('/api/logout');

    $response->assertUnauthorized();
});
