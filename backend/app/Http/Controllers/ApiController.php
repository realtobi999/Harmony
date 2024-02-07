<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\Spotify\SpotifyCreateToken;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redirect;

/**
 * The ApiController class serves as a base controller for various API controllers
 * and provides common functionalities needed across different API endpoints.
 * This class encapsulates methods for handling responses, generating error
 * responses, validating requests, and processing incoming requests in a secure
 * and standardized manner.
 *
 * Notable methods within this class include:
 *
 * 1. `sendResponseOrError`: A utility method that examines the response data and
 *    generates either a success or error JSON response. It checks for the presence
 *    of an 'error' key in the data and constructs an appropriate response based on
 *    the presence of errors.
 *
 * 2. `errorResponse`: Generates a standardized error JSON response with the given
 *    error message and HTTP status code.
 *
 * 3. `validateRequest`: Validates incoming requests by checking if the provided
 *    Harmony token corresponds to the user it is associated with. This method
 *    ensures the security and integrity of incoming requests.
 *
 * 4. `retrieveUserData`: Retrieves user data, including user ID and Spotify access
 *    token, from the request. This information is essential for processing and
 *    authenticating Spotify API requests.
 *
 * 5. `processRequest`: A protected method designed to be called by subclasses,
 *    encapsulating the common logic for handling incoming requests. It validates
 *    the request, retrieves user data, and executes the provided callback function,
 *    handling exceptions such as expired access tokens by triggering a token
 *    refresh and retrying the callback.
 *
 * Overall, the ApiController promotes code reuse and a standardized approach to
 * handling API requests, ensuring that common functionalities are encapsulated
 * and shared among various API controllers.
 */

class ApiController extends Controller
{
    public function sendResponseOrError(array|null $data): JsonResponse
    {
        if (isset($data['error'])) {
            return $this->errorResponse($data['error']['message'], $data['error']['status'] ?? 500);
        } else {
            return response()->json($data ?? "Success!", 200);
        }
    }
    
    public function errorResponse(string $message, int $status): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $status);
    }
    
    private function validateRequest(Request $request): bool
    {
        try {
            // Check if token is corresponding to the user it is associated with
            User::where([
                'id' => $request->userId,
                'harmony_token' => $token = Str::after($request->header('Authorization'), 'Bearer '),
            ])->firstOrFail();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    private function retrieveUserData(Request $request): array
    {
        $userData = [
            'id' => $request->userId,
            'access_token' => User::where('id', $request->userId)->firstOrFail()->spotify_access_token
        ];
        return $userData;
    }

    /**
     * validates a incoming request/handles the request and return the json format of the callback data
     *
     * @param callable $callback 
     * @param Request $request 
     * @return JsonResponse
     */
    protected function processRequest(callable $callback, Request $request): JsonResponse
    {
        if (!$this->validateRequest($request)) {
            return $this->errorResponse('Unauthorized', 401);
        }
        
        $userData = $this->retrieveUserData($request);

        try{
            $spotifyData = $callback($userData);
        } catch (\Exception $exception) {
            if($exception->getMessage() == 'Access token expired') {
                SpotifyCreateToken::refreshToken($userData['id']);
                return $this->errorResponse('Access token expired', 410);
            }  
        }

        return $this->sendResponseOrError($spotifyData ?? ["Success!"]);
    }
    
}
