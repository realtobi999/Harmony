<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ArtistApiTest extends TestCase
{

    public function test_that_playlist_get_requests_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        // Define the routes to test
        $routes = [
            "http://127.0.0.1:8000/api/spotify/artist/show-0TnOYISbd1XYRBk9myaseg?userId={$user->id}",
            "http://127.0.0.1:8000/api/spotify/artist/show-0TnOYISbd1XYRBk9myaseg/albums?userId={$user->id}",
            "http://127.0.0.1:8000/api/spotify/artist/show-0TnOYISbd1XYRBk9myaseg/top-tracks?userId={$user->id}",
            "http://127.0.0.1:8000/api/spotify/artist/show-0TnOYISbd1XYRBk9myaseg/related-artists?userId={$user->id}"
        ];

        foreach ($routes as $route) {
            // Make a GET request to each route
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->harmony_token,
            ])->actingAs($user)->get($route);

            // Assert the response status and JSON structure
            $response->assertStatus(200);
        }
        DB::rollBack();
    }
}
