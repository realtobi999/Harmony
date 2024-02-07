<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_that_search_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $routes = [
            "/api/spotify/search-track?q=12345&limit=10&userId={$user->id}",
        ];

        foreach ($routes as $route) {

            $response = $this->actingAs($user)->get($route, [
                'Authorization' => 'Bearer ' . $user->harmony_token
            ]);


            $response->assertStatus(200);
        }

        DB::rollBack();
    }

}
