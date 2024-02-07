<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthBasicTest extends TestCase
{
    use WithoutMiddleware;

    public function test_that_validation_works(): void
    {
        $response = $this->post('/api/register');

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => [
                'username' => ['The username field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ],
        ]);
    }

    public function test_that_registration_works(): void
    {

        DB::beginTransaction();

        $response = $this->post('/api/register', [
            'username' => 'testuser',
            'email' => 't2u6x@example.com',
            'password' => 'password2134',
            'password_confirmation' => 'password2134',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'message' => 'User created successfully.',
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 't2u6x@example.com',
            'spotify_access_token' => 'default',
            'spotify_refresh_token' => 'default',
        ]);

        DB::rollBack();
    }
}
