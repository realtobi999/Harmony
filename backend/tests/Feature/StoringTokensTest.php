<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StoringTokensTest extends TestCase
{
    public function test_that_valid_request_works(): void
    {
        DB::beginTransaction();
        
        $user = User::factory()->create();

        $response = $this->post('/api/auth/store-tokens',[
            'userId' => $user->id,
            'tokens' => [
                'access_token' => '123',
                'refresh_token' => '456'
            ]
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Tokens stored successfully.',
        ]);

        DB::rollBack();
    }

    public function test_that_invalid_request_fails(): void
    {
        $response = $this->post('/api/auth/store-tokens');
        $response->assertStatus(500);
    }

    public function test_that_tokens_are_stored(): void
    {
        DB::beginTransaction();

        $user = User::factory()->create();


        $response = $this->post('/api/auth/store-tokens',[
            'userId' => $user->id,
            'tokens' => [
                'access_token' => '123',
                'refresh_token' => '456'
            ]
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'spotify_access_token' => '123',
            'spotify_refresh_token' => '456'
        ]);
        DB::rollBack();
    }

    public function test_that_it_fails_when_user_not_found(): void
    {
        $response = $this->post('/api/auth/store-tokens',[
            'userId' => 0,
            'tokens' => [
                'access_token' => '123',
                'refresh_token' => '456'
            ]
        ]);

        $response->assertStatus(500);
    }
}
