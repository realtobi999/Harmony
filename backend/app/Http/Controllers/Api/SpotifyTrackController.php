<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\Spotify;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpotifyTrackController extends ApiController
{
    public function getTrack(Request $request, string $trackId): JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request, $trackId) {

            $queryParams = [
                'ids' => $trackId,
                'market' => $request->market ?? 'ES'
            ];

            return Spotify::makeGetRequest('getTrackUrl', $queryParams, $userData['access_token']);
        }, $request);
    }

    public function getSavedTracks(Request $request): JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $queryParams = [
                'limit' => $request->limit,
                'offset' => $request->offset,
                'market' => $request->market ?? 'ES',
            ];

            return Spotify::makeGetRequest('getSavedTracksUrl', $queryParams, $userData['access_token']);
        }, $request);
    }

    public function saveTrack(Request $request): JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $params = [
                'ids' => $request->ids
            ];

            return Spotify::makePutRequest('saveTrackUrl', $params, [], $userData['access_token']);
        }, $request);
    }

    public function unsaveTrack(Request $request): JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $params = [
                'ids' => $request->ids
            ];

            return Spotify::makeDeleteRequest('unsaveTrackUrl', $params, [], $userData['access_token']);
        }, $request);
    }

    public function getIsSaved(Request $request): JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $queryParams = [
                'ids' => $request->ids
            ];


            return Spotify::makeGetRequest('getIsSavedUrl', $queryParams, $userData['access_token']);
        }, $request);
    }

    public function getRecentTracks(Request $request): JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $queryParams = [
                'limit' => $request->limit,
                'after' => $request->after
            ];

            return Spotify::makeGetRequest('getRecentTracksUrl', $queryParams, $userData['access_token']);
        }, $request);
    }
}
