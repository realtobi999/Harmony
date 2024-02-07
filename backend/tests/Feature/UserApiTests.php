<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class UserApiTests extends TestCase
{
    public function test_that_authentication_works(): void
    {
        DB::beginTransaction();

        $user = User::factory()->create();

        $routes = [
            '/api/spotify/users/me?',
        ];

        foreach ($routes as $route) {
            $route = $route . 'userId=9999999999999999999';

            $response = $this->actingAs($user)->get($route, [
                'Authorization' => 'Bearer ' . $user->harmony_token
            ]);


            $response->assertStatus(401);
            $response->assertJson([
                'status' => 'error',
                'message' => 'Unauthorized',
            ]);
        }

        DB::rollBack();
    }

    public function test_that_get_spotify_api_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $routes = [
            "/api/spotify/users/me?userId={$user->id}",
            "http://127.0.0.1:8000/api/spotify/users/top-items?userId={$user->id}&timeRange=short_term&type=tracks",
            "http://127.0.0.1:8000/api/spotify/users/?userId={$user->id}&profileId=smedjan",
            "http://127.0.0.1:8000/api/spotify/users/followed-artists?userId={$user->id}",
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route, [
                'Authorization' => 'Bearer ' . $user->harmony_token
            ]);

            $response->assertStatus(200);
        }

        DB::rollBack();
    }

    public function test_that_put_spotify_api_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $routes = [
            "http://127.0.0.1:8000/api/spotify/users/follow-playlist?playlistId=3cEYpjA9oz9GiPac4AsH4n&userId={$user->id}",
        ];

        foreach ($routes as $route) {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->harmony_token
            ])->actingAs($user)->put($route);

            $response->assertStatus(200);
        }

        DB::rollBack();
    }

    public function test_that_delete_spotify_api_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $routes = [
            "http://127.0.0.1:8000/api/spotify/users/unfollow-playlist?playlistId=3cEYpjA9oz9GiPac4AsH4n&userId={$user->id}",
        ];

        foreach ($routes as $route) {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->harmony_token
            ])->actingAs($user)->delete($route);

            $response->assertStatus(200);
        }
    }

    public function test_that_follow_api_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $route = "http://127.0.0.1:8000/api/spotify/users/follow-artist?userId={$user->id}";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->harmony_token
        ])-> actingAs($user)->put($route, [
            'ids' => '5K4W6rqBFWDnAN6FQUkS6x',
        ]);

        $response->assertStatus(200);

        DB::rollBack();
    }

    public function test_that_unfollow_api_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $route = "http://127.0.0.1:8000/api/spotify/users/unfollow-artist?userId={$user->id}";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->harmony_token
        ])-> actingAs($user)->delete($route, [
            'ids' => '5K4W6rqBFWDnAN6FQUkS6x',
        ]);

        $response->assertStatus(200);

        DB::rollBack();
    }

    public function test_that_check_if_following_api_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $route = "http://127.0.0.1:8000/api/spotify/users/following-artist?userId={$user->id}&ids=5K4W6rqBFWDnAN6FQUkS6x,0oSGxfWSnnOXhD2fKuz2Gy";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->harmony_token
        ])-> actingAs($user)->get($route);

        $response->assertStatus(200);

        DB::rollBack();
    }

    public function test_that_check_if_following_playlist_api_works(): void
    {
        DB::beginTransaction();
        // Create a user using the factory
        $user = User::factory()->create();

        // Attempt to find a user with ID 7 and get its Spotify access token
        $sourceUser = User::findOrFail(7);
        $user->spotify_access_token = $sourceUser->spotify_access_token;
        $user->save(); // Save changes to the new user

        $route = "http://127.0.0.1:8000/api/spotify/users/following/playlist?userId={$user->id}&playlistId=3cEYpjA9oz9GiPac4AsH4n&ids=jmperezperez";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->harmony_token
        ])-> actingAs($user)->get($route);

        $response->assertStatus(200);
        DB::rollBack();
    }
}
