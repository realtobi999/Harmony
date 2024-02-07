<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PlaylistApiTests extends TestCase
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

        $routes = [
            "http://127.0.0.1:8000/api/spotify/playlist/show-3cEYpjA9oz9GiPac4AsH4n?userId={$user->id}",
            "http://127.0.0.1:8000/api/spotify/playlist/show/saved?userId={$user->id}&limit=10",
            "http://127.0.0.1:8000/api/spotify/playlist/show/user/smedjan?userId={$user->id}&limit=10",
            "http://127.0.0.1:8000/api/spotify/playlist/show/featured-playlists?userId={$user->id}&limit=10",
        ];

        foreach ($routes as $route) {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->harmony_token
            ])->actingAs($user)->get($route);


            $response->assertStatus(200);
        }

        DB::rollBack();
    }
}
