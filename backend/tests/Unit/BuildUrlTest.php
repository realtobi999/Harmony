<?php

namespace Tests\Unit;

use App\Service\Spotify\SpotifyUrlBuilder;
use PHPUnit\Framework\TestCase;

class BuildUrlTest extends TestCase
{
    public function test_example(): void
    {
        $url = "/v1/search";
        $queryParams = [
            "q" => "test",
            "type" => "track",
            "market" => "ES",
            "limit" => 10,
            "offset" => 0,
        ];

        $buildedUrl = SpotifyUrlBuilder::buildUrl($url, $queryParams);

        $this->assertEquals("https://api.spotify.com/v1/search?q=test&type=track&market=ES&limit=10&offset=0", $buildedUrl);
    }
}
