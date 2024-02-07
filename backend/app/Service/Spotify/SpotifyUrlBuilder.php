<?php

declare(strict_types=1);

namespace App\Service\Spotify;

use Illuminate\Support\Arr;

/**
 * The SpotifyUrlBuilder class provides a centralized mechanism for constructing
 * URLs for various Spotify API endpoints. It encapsulates the logic to build
 * specific URLs based on the type of Spotify API resource and the required
 * parameters. This class promotes code organization and reusability by separating
 * the URL construction logic from the rest of the application, making it easier
 * to maintain and extend.
 
 * By utilizing the methods provided by SpotifyUrlBuilder, developers can easily
 * generate the correct URLs for various Spotify API requests, promoting a clean
 * and consistent approach to interacting with the Spotify API within the
 * application.
 */

class SpotifyUrlBuilder
{
    public static function getRefreshTokenUrl(): string
    {
        return "https://accounts.spotify.com/api/token";
    }

    public static function buildUrl(string $endpoint, array $queryParams = []): string
    {
        $url = "https://api.spotify.com" . $endpoint;

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        return $url;
    }

    public static function getCurrentUserUrl(array $queryParams = []): string
    {
        return self::buildUrl('/v1/me', $queryParams);
    }

    public static function getTopItemsUrl(array $queryParams = []): string
    {
        $type = Arr::pull($queryParams, 'type');
        return self::buildUrl("/v1/me/top/{$type}", $queryParams);
    }

    public static function getUserUrl(array $queryParams = []): string
    {
        $profileId = Arr::pull($queryParams, 'user_id');
        return self::buildUrl("/v1/users/{$profileId}", $queryParams);
    }

    public static function followPlaylistUrl(array $queryParams = []): string
    {
        $playlistId = Arr::pull($queryParams, 'playlist_id');
        return self::buildUrl("/v1/playlists/{$playlistId}/followers", $queryParams);
    }

    public static function unfollowPlaylistUrl(array $queryParams = []): string
    {
        $playlistId = Arr::pull($queryParams, 'playlist_id');
        return self::buildUrl("/v1/playlists/{$playlistId}/followers", $queryParams);
    }

    public static function getFollowedArtistsUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/following", $queryParams);
    }

    public static function followUrl(array $queryParams = []): string
    {
            return self::buildUrl("/v1/me/following", $queryParams);
    }

    public static function unfollowUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/following", $queryParams);
    }

    public static function getIsFollowingUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/following/contains", $queryParams);
    }

    public static function getIsFollowingPlaylistUrl(array $queryParams = []): string
    {
        $playlistId = Arr::pull($queryParams, 'playlist_id');
        return self::buildUrl("/v1/playlists/{$playlistId}/followers/contains", $queryParams);
    }

    public static function getTrackUrl(array $queryParams = []): string
    {
        $trackId = Arr::pull($queryParams, 'ids');
        return self::buildUrl("/v1/tracks/{$trackId}", $queryParams);
    }

    public static function getSavedTracksUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/tracks", $queryParams);
    }

    public static function saveTrackUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/tracks", $queryParams);
    }

    public static function unsaveTrackUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/tracks", $queryParams);
    }

    public static function getIsSavedUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/tracks/contains", $queryParams);
    }

    public static function searchUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/search", $queryParams);
    }

    public static function getPlaylistUrl(array $queryParams = []): string
    {
        $playlistId = Arr::pull($queryParams, 'playlist_id');
        return self::buildUrl("/v1/playlists/{$playlistId}", $queryParams);
    }

    public static function getSavedPlaylistsUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/playlists", $queryParams);
    }

    public static function getUserPlaylistsUrl(array $queryParams = []): string
    {
        $profileId = Arr::pull($queryParams, 'user_id');
        return self::buildUrl("/v1/users/{$profileId}/playlists", $queryParams);
    }

    public static function getFeaturedPlaylistsUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/browse/featured-playlists", $queryParams);
    }

    public static function getArtistUrl(array $queryParams = []): string
    {
        $artistId = Arr::pull($queryParams, 'artist_id');
        return self::buildUrl("/v1/artists/{$artistId}", $queryParams);
    }

    public static function getArtistAlbumsUrl(array $queryParams = []): string
    {
        $artistId = Arr::pull($queryParams, 'artist_id');
        return self::buildUrl("/v1/artists/{$artistId}/albums", $queryParams);
    }

    public static function getArtistTopTracksUrl(array $queryParams = []): string
    {
        $artistId = Arr::pull($queryParams, 'artist_id');
        return self::buildUrl("/v1/artists/{$artistId}/top-tracks", $queryParams);
    }

    public static function getRelatedArtistsUrl(array $queryParams = []): string
    {
        $artistId = Arr::pull($queryParams, 'artist_id');
        return self::buildUrl("/v1/artists/{$artistId}/related-artists", $queryParams);
    }

    public static function getAlbumUrl(array $queryParams = []): string
    {
        $albumId = Arr::pull($queryParams, 'album_id');
        return self::buildUrl("/v1/albums/{$albumId}", $queryParams);
    }

    public static function getAlbumTracksUrl(array $queryParams = []): string
    {
        $albumId = Arr::pull($queryParams, 'album_id');
        return self::buildUrl("/v1/albums/{$albumId}/tracks", $queryParams);
    }

    public static function getSavedAlbumsUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/albums", $queryParams);
    }

    public static function saveAlbumUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/albums", $queryParams);
    }

    public static function unsaveAlbumUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/albums", $queryParams);
    }

    public static function getIsSavedAlbumUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/albums/contains", $queryParams);
    }

    public static function getRecentTracksUrl(array $queryParams = []): string
    {
        return self::buildUrl("/v1/me/player/recently-played", $queryParams);
    }
}
