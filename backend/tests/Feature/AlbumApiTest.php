<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AlbumApiTest extends TestCase
{
    public function test_that_get_albums_request_works() : void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $routes = [
            "http://127.0.0.1:8000/api/spotify/album/show-4aawyAB9vmqN3uQ7FjRGTy?userId={$user->id}",
            "http://127.0.0.1:8000/api/spotify/album/show-4aawyAB9vmqN3uQ7FjRGTy/tracks?userId={$user->id}", 
            "http://127.0.0.1:8000/api/spotify/album/show/saved?userId={$user->id}",
            "http://127.0.0.1:8000/api/spotify/album/is-saved?userId={$user->id}&ids=382ObEPsp2rxGrnsizN5TX,1A2GTWGtFfWp7KSQTwWOyo"
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route, [
                'Authorization' => 'Bearer ' . $user->harmony_token
            ]);

            $response->assertStatus(200);
        }

        DB::rollBack();
    }

    public function test_that_saving_album_works() : void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $route = "http://127.0.0.1:8000/api/spotify/album/save?userId={$user->id}";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->harmony_token
        ])->actingAs($user)->put($route, [
            'ids' => '382ObEPsp2rxGrnsizN5TX,4J49WkHDnNWjYkGdsC0TFj'
        ]);

        $response->assertStatus(200);
    }

    public function test_that_unsaving_album_works() : void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $route = "http://127.0.0.1:8000/api/spotify/album/unsave?userId={$user->id}";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->harmony_token
        ])->actingAs($user)->delete($route, [
            'ids' => '382ObEPsp2rxGrnsizN5TX',
        ]);

        $response->assertStatus(200);
    }
}
