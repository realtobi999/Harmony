<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\Spotify;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class SpotifyUsersController extends ApiController
{
    public function getCurrentUser(Request $request) : JsonResponse
    {
        return $this->processRequest(function ($userData){
            return Spotify::makeGetRequest('getCurrentUserUrl', [], $userData['access_token']);
        }, $request);
    }

    public function getTopItems(Request $request) : JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $queryParams = [
                'type' => $request->type,
                'time_range' => $request->timeRange,
                'limit' => $request->limit
            ];

            return Spotify::makeGetRequest('getTopItemsUrl', $queryParams, $userData['access_token']);
        }, $request);
    }

    public function getUser(Request $request) : JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $queryParams = [
                'user_id' => $request->profileId
            ];

            return Spotify::makeGetRequest('getUserUrl', $queryParams, $userData['access_token']);
        }, $request);
    }

    public function followPlaylist(Request $request) : JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $queryParams = [
                'playlist_id' => $request->playlistId,
            ];
    
            $payload = [
                'public' => true
            ];

            return Spotify::makePutRequest('followPlaylistUrl', $queryParams, $payload, $userData['access_token']);
        }, $request);
    }

    public function unfollowPlaylist(Request $request) : JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $queryParams = [
                'playlist_id' => $request->playlistId,
            ];

            return Spotify::makeDeleteRequest('unfollowPlaylistUrl', $queryParams, [], $userData['access_token']);
        }, $request);
    }

    public function getFollowedArtists(Request $request) : JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {
            
            $queryParams = [
                'limit' => $request->limit,
                'type' => 'artist'
            ];
    
            return Spotify::makeGetRequest('getFollowedArtistsUrl', $queryParams, $userData['access_token']);
        }, $request);
    }

    public function follow(Request $request, string $type) : JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request, $type) {
            
            $queryParams = [
                'type' => $type,
                'ids' => $request->ids,
            ];
    

            return Spotify::makePutRequest('followUrl', $queryParams, [], $userData['access_token']);
        }, $request);
    }
    
    public function unfollow(Request $request, string $type) : JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request, $type) {

            $queryParams = [
                'type' => $type,
                'ids' => $request->ids,
            ];

            return Spotify::makeDeleteRequest('unfollowUrl', $queryParams, [], $userData['access_token']);
        }, $request);
    }

    public function getIsFollowing(Request $request, string $type) : JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request, $type) {

            $queryParams = [
                'type' => $type,
                'ids' => $request->ids
            ];

            return Spotify::makeGetRequest('getIsFollowingUrl', $queryParams, $userData['access_token']);
        }, $request);
    }

    public function getIsFollowingPlaylist(Request $request) : JsonResponse
    {
        return $this->processRequest(function ($userData) use ($request) {

            $queryParams = [
                'playlist_id' => $request->playlistId,
                'ids' => $request->userId,
            ];
    
            return Spotify::makeGetRequest('getIsFollowingPlaylistUrl', $queryParams, $userData['access_token']);
        }, $request);
    }
}
