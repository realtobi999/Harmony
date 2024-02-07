<?php

declare(strict_types=1);

namespace App\Service\Spotify;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * The SpotifyCreateToken class plays a crucial role in the authentication flow
 * within the application by facilitating user authorization with Spotify and
 * managing access tokens. This class encapsulates functionalities for initiating
 * the user authorization process, handling the callback after successful
 * authorization, and refreshing access tokens to ensure seamless and secure
 * interactions with the Spotify API.
 *
 * Methods within this class include:
 *
 * 1. `redirect`: Initiates the Spotify user authorization process by generating a
 *    random state for security, constructing the Spotify authorization URL with
 *    appropriate query parameters, and redirecting the user to the authorization
 *    page. This method ensures the necessary scopes are included to request
 *    specific user permissions.
 *
 * 2. `callback`: Processes the callback from Spotify after the user successfully
 *    authorizes the application. It extracts the authorization code and state
 *    from the callback request, checks for a valid state to prevent CSRF attacks,
 *    and then sends a POST request to the Spotify token endpoint to obtain the
 *    access token.
 *
 * 3. `refreshToken`: Refreshes the access token using the refresh token obtained
 *    during the authorization process. This method retrieves the refresh token
 *    from the database, sends a POST request to the Spotify token endpoint with
 *    the necessary parameters, and updates the user's database with the new access
 *    token. This ensures that the application maintains continuous access to
 *    Spotify resources without requiring user reauthorization.
 *
 * The class emphasizes a modular and organized approach to managing Spotify
 * authentication concerns, providing a comprehensive solution for integrating
 * Spotify functionality into the application securely and efficiently.
 */

class SpotifyCreateToken
{

    /**
     * Redirects the user to the Spotify authorization URL for user consent to access
     * their Spotify account. Generates a random state for security, constructs query
     * parameters for the Spotify authorization URL, and redirects the user to the
     * authorization URL.
     *
     * @return string
     */
    public static function redirect(): string
    {
        // Generate a random state for security
        $state = Str::random(16);

        $scope ='user-read-private user-read-email user-top-read user-read-recently-played 
                playlist-read-private playlist-modify-private playlist-modify-public 
                user-library-modify user-follow-modify playlist-modify-private user-follow-read
                user-library-read';

        // Build query parameters for Spotify authorization URL
        $queryParams = [
            'response_type' => 'code',
            'client_id' => config('services.spotify.client_id'),
            'redirect_uri' => config('services.spotify.redirect_uri'),
            'state' => $state,
            'scope' => $scope,
            'show_dialog' => true,
        ];

        // Construct the Spotify authorization URL and redirect the user
        $spotifyAuthUrl = 'https://accounts.spotify.com/authorize?' . http_build_query($queryParams);
        return $spotifyAuthUrl;
    }


    /**
     * Handles the callback from Spotify after user authorization.
     *
     * @param Request $request
     * @return array
     */
    public static function callback(Request $request): array
    {
        // Obtain the authorization code and state from the callback request
        $code = $request->query('code');
        $state = $request->query('state');

        // Check for a valid state to prevent CSRF attacks
        if ($state === null) {
            return redirect('/#' . http_build_query(['error' => 'state_mismatch']));
        }

        // Spotify token endpoint URL
        $spotifyTokenUrl = 'https://accounts.spotify.com/api/token';

        // Options for obtaining access token using authorization code
        $authOptions = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('services.spotify.redirect_uri'),
            'client_id' => config('services.spotify.client_id'),
            'client_secret' => config('services.spotify.client_secret'),
        ];

        // Send a POST request to Spotify token endpoint to obtain access token
        $response = Http::asForm()->post($spotifyTokenUrl, $authOptions);
        $responseData = $response->json();

        return $responseData;
    }

    public static function refreshToken($userId) : bool
    {
        // Retrieve the refresh token from the session
        $refreshToken = User::findOrFail($userId)->spotify_refresh_token;

        // Define query parameters for refreshing access token
        $queryParams = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken
        ];

        // Send a POST request to Spotify token endpoint to refresh access token
        $response = Http::asForm()
            ->withBasicAuth(env('SPOTIFY_CLIENT_ID'), env('SPOTIFY_CLIENT_SECRET'))
            ->post(SpotifyUrlBuilder::getRefreshTokenUrl(), $queryParams)
            ->json();

        // Update the database with the new access token
        $newAccessToken = $response['access_token'];


        User::where('id', $userId)->update([
            'spotify_access_token' => $newAccessToken
        ]);

        return true;
    }
}
