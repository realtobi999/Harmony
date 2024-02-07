<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\Spotify;
use App\Http\Controllers\ApiController;
use App\Service\Spotify\SpotifyCreateToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpotifyPlaylistController extends ApiController
{
    public function getPlaylist(Request $request, string $playlistId) : JsonResponse
    {
        return $this->processRequest(function ($userData) use($request, $playlistId) {

            $params = [
                'market' => $request->market ?? 'ES',
                'playlist_id' => $playlistId,
                'fields' => $request->fields ?? 'images(url),uri,name,id,owner(display_name,id),tracks(items(added_at,track(name,id,album(name,id,images(url),artists(name)))))',
            ];
    
            return Spotify::makeGetRequest('getPlaylistUrl', $params, $userData['access_token']);
        }, request());
    }

    public function getSavedPlaylists(Request $request) : JsonResponse
    {
        return $this->processRequest(function ($userData) use($request) {

            $params = [
                'limit' => $request->limit,
                'offset' => $request->offset
            ];

            return Spotify::makeGetRequest('getSavedPlaylistsUrl', $params, $userData['access_token']);
        }, $request); 
    }

    public function getUserPlaylists(Request $request, string $userId) : JsonResponse
    {
        return $this->processRequest(function ($userData) use($request, $userId) {

            $params = [
                'limit' => $request->limit,
                'user_id' => $userId,
                'offset' => $request->offset
            ];

            return Spotify::makeGetRequest('getUserPlaylistsUrl', $params, $userData['access_token']);
        }, $request);
    }

    public function getFeaturedPlaylists(Request $request) : JsonResponse
    {
        return $this->processRequest(function ($userData) use($request) {

            $params = [
                'locale' => $request->locale ?? 'sv_SE',
                'limit' => $request->limit,
                'offset' => $request->offset
            ];

            return Spotify::makeGetRequest('getFeaturedPlaylistsUrl', $params, $userData['access_token']);
        }, $request);
    }
}
