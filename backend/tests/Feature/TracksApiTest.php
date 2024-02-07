<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TracksApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_that_get_track_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $routes = [
            "http://127.0.0.1:8000/api/spotify/track/show-11dFghVXANMlKmJXsNCbNl?userId={$user->id}",
            "http://127.0.0.1:8000/api/spotify/track/show/saved?userId={$user->id}&limit=10",
            "http://127.0.0.1:8000/api/spotify/track/is-saved?userId={$user->id}&ids=11dFghVXANMlKmJXsNCbNl",
            "http://127.0.0.1:8000/api/spotify/track/show/recent?userId={$user->id}&limit=10",
        ];

        foreach ($routes as $route) {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->harmony_token
            ])->actingAs($user)->get($route);
    
            $response->assertStatus(200);
        }

        DB::rollBack();
    }

    public function test_that_save_tracks_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $route = "http://127.0.0.1:8000/api/spotify/track/save?userId={$user->id}";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->harmony_token
        ])->actingAs($user)->put($route, [
            'ids' => '11dFghVXANMlKmJXsNCbNl'
        ]);

        $response->assertStatus(200);
    }

    public function test_that_unsave_track_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user


        $route = "http://127.0.0.1:8000/api/spotify/track/unsave?userId={$user->id}";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->harmony_token
        ])->actingAs($user)->delete($route, [
            'ids' => '11dFghVXANMlKmJXsNCbNl',
        ]);

        $response->assertStatus(200);

        DB::rollBack();
    }

    public function test_that_get_is_saved_track_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $route = "http://127.0.0.1:8000/api/spotify/track/is-saved?ids=11dFghVXANMlKmJXsNCbNl&userId={$user->id}";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->harmony_token
        ])->actingAs($user)->get($route);

        $response->assertStatus(200);

        DB::rollBack();
    }
}
