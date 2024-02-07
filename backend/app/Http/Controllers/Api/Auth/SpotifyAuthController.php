<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Service\Spotify\SpotifyCreateToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The SpotifyAuthController class extends the ApiController and specifically
 * focuses on handling Spotify authentication-related actions within the
 * application. It leverages the SpotifyCreateToken class to facilitate user
 * redirection to Spotify for authorization and handling the callback after
 * successful authorization.
 *
 * Notable methods within this class include:
 *
 * 1. `redirect`: Redirects the user to the Spotify authorization URL for user
 *    consent to access their Spotify account. It utilizes the SpotifyCreateToken
 *    class for this process and gracefully handles exceptions by returning an
 *    error response if an issue occurs during redirection.
 *
 * 2. `callback`: Handles the callback from Spotify after user authorization,
 *    extracting the authorization code and state from the callback request. It
 *    utilizes the SpotifyCreateToken class to exchange the authorization code
 *    for access and refresh tokens. The response includes success or error details.
 *
 * 3. `storeTokensIntoUserTable`: Stores the retrieved Spotify access and refresh
 *    tokens into the user table in the database. It verifies the existence of the
 *    user, updates the tokens, and returns a success or error response based on
 *    the outcome.
 *
 * This class seamlessly integrates Spotify authentication into the application,
 * providing clean and standardized API endpoints for user interactions with
 * Spotify. It extends the functionality of the base ApiController to handle
 * authentication-specific operations.
 */

class SpotifyAuthController extends ApiController
{
    /**
     * A function that redirects to Spotify and handles exceptions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function redirect(): JsonResponse
    {
        try {
            return response()->json(['url' => SpotifyCreateToken::redirect()]); 
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Callback function for handling Spotify API callback.
     *
     * @param Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse The JSON response object
     */
    public function callback(Request $request): \Illuminate\Http\JsonResponse
    {
        $spotifyResponseData = SpotifyCreateToken::callback($request);

        // Check if there was an error
        if (isset($spotifyResponseData['error'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'something went wrong: ' . $spotifyResponseData['error'],
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in with spotify successfully.',
            'tokens' => [
                'access_token' => $spotifyResponseData['access_token'],
                'refresh_token' => $spotifyResponseData['refresh_token']
            ]
        ], 200);
    }


    public function storeTokensIntoUserTable(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Verify user existence
            $user = User::findOrFail($request->userId);

            // Update user tokens
            $user->spotify_access_token = $request->tokens['access_token'];
            $user->spotify_refresh_token = $request->tokens['refresh_token'];
            $user->save(); // Save changes to the database

            return response()->json([
                'status' => 'success',
                'message' => 'Tokens stored successfully.',
            ], 201);
        } catch (\Exception $e) {
            // Handle exceptions (user not found or other issues)
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
}
