<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpotifyAuthTest extends TestCase
{
    public function test_that_it_redirects(): void
    {
        $response = $this->get('/api/auth/spotify/redirect');

        $response ->assertStatus(200);
    }
}
