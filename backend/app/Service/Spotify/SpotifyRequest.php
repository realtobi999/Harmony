<?php

declare(strict_types=1);

namespace App\Service\Spotify;

use Illuminate\Support\Facades\Http;

/**
 * The SpotifyRequest class encapsulates methods for making HTTP requests to the
 * Spotify API. It provides a convenient and standardized way to interact with
 * various Spotify endpoints, handling GET, DELETE, and PUT requests. These methods
 * include functionalities to construct the request URL, set necessary headers,
 * and handle response data.
 *
 * Methods within this class include:
 *
 * 1. `makeGetRequest`: Makes a GET request to the specified Spotify API endpoint
 *    with the provided parameters and access token. It utilizes the SpotifyUrlBuilder
 *    to construct the URL and handles common error scenarios by checking for
 *    expired access tokens.
 *
 * 2. `makeDeleteRequest`: Makes a DELETE request to the specified Spotify API
 *    endpoint, sending the specified payload and using the provided access token.
 *    Similar to the GET request method, it leverages the SpotifyUrlBuilder for URL
 *    construction and includes error handling for potential issues.
 *
 * 3. `makePutRequest`: Makes a PUT request to the specified Spotify API endpoint,
 *    utilizing the provided parameters, payload, and access token. It follows a
 *    consistent pattern of URL construction, header setup, and error handling.
 *
 * 4. `catchErrors`: A private method dedicated to catching specific errors in the
 *    Spotify API responses, such as expired access tokens. It throws an exception
 *    if an access token has expired, ensuring proper handling and informing the
 *    calling code about the issue.
 *
 * Overall, the SpotifyRequest class promotes a clean and modular approach to
 * handling Spotify API requests, abstracting away common concerns and providing
 * a reliable interface for communication with the Spotify API endpoints.
 */

class SpotifyRequest
{
    /**
     * Makes a GET request to the Spotify API.
     *
     * @param string $methodForUrl The method for the URL.
     * @param array $params The parameters for the request.
     * @param string $accessToken The access token for the request.
     * @throws Some_Exception_Class Description of exception
     * @return array
     */
    public static function makeGetRequest(string $methodForUrl, array $params, string $accessToken): array|bool
    {
        $url = SpotifyUrlBuilder::$methodForUrl($params);
        $data = Http::withHeaders(['Authorization' => 'Bearer ' . $accessToken])
            ->get($url)
            ->json();

        self::catchErrors($data);

        return $data;
    }

    /**
     * Make a delete request to the specified URL using the given parameters, payload, and access token.
     *
     * @param string $methodForUrl The method for the URL
     * @param array $params The parameters
     * @param array $payload The payload
     * @param string $accessToken The access token
     * @throws Some_Exception_Class Description of exception
     * @return null|array array for error messages
     */
    public static function makeDeleteRequest(string $methodForUrl, array $params, array $payload, string $accessToken): null|array
    {
        $url = SpotifyUrlBuilder::$methodForUrl($params);
        $data = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->delete($url, $payload)->json();

        self::catchErrors($data);

        return $data;
    }

    /**
     * Make a PUT request to the specified URL using the given parameters and payload.
     *
     * @param string $methodForUrl The method for the URL.
     * @param array $params The parameters for the URL.
     * @param array $payload The payload for the request.
     * @param string $accessToken The access token for authentication.
     * @throws Some_Exception_Class description of exception
     * @return null|array array for error messages
     */
    public static function makePutRequest(string $methodForUrl, array $params, array $payload, string $accessToken): null|array
    {
        $url = SpotifyUrlBuilder::$methodForUrl($params);
        $data = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->put($url, $payload)->json();

        self::catchErrors($data);

        return $data;
    }

    /**
     * Caches access token expired error and throws an exception
     *
     * @param array|null $data 
     * @throws \Exception Access token expired
     * @return void
     */
    private static function catchErrors(array|null $data): void
    {
        if (isset($data['error']['message']) && $data['error']['message'] == 'The access token expired') {
            throw new \Exception('Access token expired');
        }
    }
}
