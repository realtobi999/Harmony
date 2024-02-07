<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\SpotifyAuthController;
use App\Http\Controllers\Api\SpotifyAlbumController;
use App\Http\Controllers\Api\SpotifyArtistController;
use App\Http\Controllers\Api\SpotifyPlaylistController;
use App\Http\Controllers\Api\SpotifySearchController;
use App\Http\Controllers\Api\SpotifyTrackController;
use App\Http\Controllers\Api\SpotifyUsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:sanctum', 'as' => 'api.'], function () {
    Route::prefix('spotify')->group(function () {

        Route::get('/search-{type}', [SpotifySearchController::class, 'search']); // Search API - TESTED

        Route::prefix('users')->group(function () { // Users API - TESTED
            Route::get('/me', [SpotifyUsersController::class, 'getCurrentUser']);
            Route::get('/top-items', [SpotifyUsersController::class, 'getTopItems']);
            Route::get('/', [SpotifyUsersController::class, 'getUser']);
            Route::put('/follow-playlist', [SpotifyUsersController::class, 'followPlaylist']);
            Route::delete('/unfollow-playlist', [SpotifyUsersController::class, 'unfollowPlaylist']);
            Route::get('/followed-artists', [SpotifyUsersController::class, 'getFollowedArtists']);
            Route::put('/follow-{type}', [SpotifyUsersController::class, 'follow']);
            Route::delete('/unfollow-{type}', [SpotifyUsersController::class, 'unfollow']);
            Route::get('/following-{type}', [SpotifyUsersController::class, 'getIsFollowing']);
            Route::get('/following/playlist', [SpotifyUsersController::class, 'getIsFollowingPlaylist']);
        });

        Route::prefix('track')->group(function () { // Track API - TESTED
            Route::get('/show-{trackId}', [SpotifyTrackController::class, 'getTrack']);
            Route::get('/show/saved', [SpotifyTrackController::class, 'getSavedTracks']);
            Route::put('/save', [SpotifyTrackController::class, 'saveTrack']);
            Route::delete('/unsave', [SpotifyTrackController::class, 'unsaveTrack']);
            Route::get('/is-saved', [SpotifyTrackController::class, 'getIsSaved']);
            Route::get('/show/recent', [SpotifyTrackController::class, 'getRecentTracks']);
        });

        Route::prefix('playlist')->group(function () { // Playlist API - TESTED
            Route::get('/show-{playlistId}', [SpotifyPlaylistController::class, 'getPlaylist']);
            Route::get('/show/saved', [SpotifyPlaylistController::class, 'getSavedPlaylists']);
            Route::get('/show/user/{userId}', [SpotifyPlaylistController::class, 'getUserPlaylists']);
            Route::get('/show/featured-playlists', [SpotifyPlaylistController::class, 'getFeaturedPlaylists']);
        });

        Route::prefix('artist')->group(function () {
            Route::get('/show-{artistId}', [SpotifyArtistController::class, 'getArtist']);
            Route::get('/show-{artistId}/albums', [SpotifyArtistController::class, 'getArtistAlbums']);
            Route::get('/show-{artistId}/top-tracks', [SpotifyArtistController::class, 'getArtistTopTracks']);
            Route::get('/show-{artistId}/related-artists', [SpotifyArtistController::class, 'getRelatedArtists']);
        });

        Route::prefix('album')->group(function () {
            Route::get('/show-{albumId}', [SpotifyAlbumController::class,'getAlbum']);
            Route::get('/show-{albumId}/tracks', [SpotifyAlbumController::class,'getAlbumTracks']);
            Route::get('/show/saved', [SpotifyAlbumController::class,'getSavedAlbums']);
            Route::put('/save', [SpotifyAlbumController::class, 'saveAlbum']);
            Route::delete('/unsave', [SpotifyAlbumController::class, 'unsaveAlbum']);
            Route::get('/is-saved', [SpotifyAlbumController::class, 'getIsSaved']);
        });
    });
});

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"])->name("login");
Route::get('/login', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized, please login',
    ], 401);
});

Route::prefix('/auth')->group(function () {
    Route::get('/spotify/redirect', [SpotifyAuthController::class, 'redirect']);
    Route::get('/callback', [SpotifyAuthController::class, 'callback']);
    Route::post('/store-tokens', [SpotifyAuthController::class, 'storeTokensIntoUserTable']);
    Route::get('/verify-token', [SpotifyAuthController::class, 'verifyToken']);
});
