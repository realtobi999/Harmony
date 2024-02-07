<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\Spotify;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpotifyAlbumController extends ApiController
{
    public function getAlbum(Request $request, string $albumId)
    {
        return $this->processRequest(function ($userData) use ($request, $albumId) {
            $params = [
                'album_id' => $albumId,
                'market' => $request->market ?? 'ES',
            ];

            return Spotify::makeGetRequest('getAlbumUrl', $params, $userData['access_token']);
        }, $request);
    }

    public function getAlbumTracks(Request $request, string $albumId)
    {
        return $this->processRequest(function ($userData) use ($request, $albumId) {
            $params = [
                'album_id' => $albumId,
                'limit' => $request->limit,
                'offset' => $request->offset,
                'market' => $request->market ?? 'ES',
            ];

            return Spotify::makeGetRequest('getAlbumTracksUrl', $params, $userData['access_token']);
        }, $request);
    }

    public function getSavedAlbums(Request $request)
    {
        return $this->processRequest(function ($userData) use ($request) {
            $params = [
                'limit' => $request->limit,
                'offset' => $request->offset,
                'market' => $request->market ?? 'ES',
            ];

            return Spotify::makeGetRequest('getSavedAlbumsUrl', $params, $userData['access_token']);
        }, $request);
    }

    public function saveAlbum(Request $request)
    {
        return $this->processRequest(function ($userData) use ($request) {
            $params = [
                'ids' => $request->ids
            ];

            return Spotify::makePutRequest('saveAlbumUrl', $params, [], $userData['access_token']);
        }, $request);
    }

    public function unsaveAlbum(Request $request)
    {
        return $this->processRequest(function ($userData) use ($request) {
            $params = [
                'ids' => $request->ids
            ];

            return Spotify::makeDeleteRequest('unsaveAlbumUrl', $params, [], $userData['access_token']);
        }, $request);
    }

    public function getIsSaved(Request $request)
    {
        return $this->processRequest(function ($userData) use ($request) {
            $params = [
                'ids' => $request->ids
            ];

            return Spotify::makeGetRequest('getIsSavedAlbumUrl', $params, $userData['access_token']);
        }, $request);
    }
}
