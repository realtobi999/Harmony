<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\Spotify;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpotifyArtistController extends ApiController
{
    public function getArtist(Request $request, string $artistId) : JsonResponse
    {
        return $this->processRequest(function ($userData) use($request, $artistId) {
            return Spotify::makeGetRequest('getArtistUrl', ['artist_id' => $artistId], $userData['access_token']);
        }, $request);
    }

    public function getArtistAlbums(Request $request, string $artistId) : JsonResponse
    {
        return $this->processRequest(function ($userData) use($request, $artistId) {

            $params = [
                'artist_id' => $artistId,
                'limit' => $request->limit,
                'offset' => $request->offset,
                'market' => $request->market ?? 'ES',
            ];

            return Spotify::makeGetRequest('getArtistAlbumsUrl', $params, $userData['access_token']);
        }, $request);
    }

    public function getArtistTopTracks(Request $request, string $artistId) : JsonResponse
    {
        return $this->processRequest(function ($userData) use($request, $artistId) {

            $params = [
                'artist_id' => $artistId,
                'market' => $request->market ?? 'ES',
            ];
    
            return Spotify::makeGetRequest('getArtistTopTracksUrl', $params, $userData['access_token']);
        }, $request);
    }

    public function getRelatedArtists(Request $request, string $artistId) : JsonResponse
    {
        return $this->processRequest(function ($userData) use($request, $artistId) {

            $params = [
                'artist_id' => $artistId,
            ];

            return Spotify::makeGetRequest('getRelatedArtistsUrl', $params, $userData['access_token']);
        }, $request);
    }
}
