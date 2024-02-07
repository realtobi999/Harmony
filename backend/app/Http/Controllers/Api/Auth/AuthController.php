<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\RetryMiddleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

/**
 * The AuthController class handles user authentication-related actions within
 * the application, including user registration and login. It extends the base
 * Controller class and provides methods to create a new user account and handle
 * user login attempts.
 *
 * Notable methods within this class include:
 *
 * 1. `register`: Handles the user registration process by validating the
 *    incoming request data, creating a new user with the provided credentials,
 *    and responding with a success or error JSON message based on the outcome.
 *
 * 2. `login`: Manages the user login process by validating the provided email
 *    and password, attempting to authenticate the user, and generating an
 *    authentication token upon successful login. The generated token is then
 *    associated with the user in the database, and the response includes the
 *    authentication token or an error message.
 *
 * Both methods follow RESTful conventions, returning appropriate JSON responses
 * with status and message details. Additionally, the `login` method generates and
 * associates a Harmony token for subsequent authenticated API requests.
 *
 * Overall, the AuthController facilitates user authentication operations and
 * serves as a key component in managing user accounts within the application.
 */

class AuthController extends Controller
{
    /**
     * Register a new user based on the given request data.
     *
     * @param Request $request The request data to validate and create the user.
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $attributes = $request->validate([
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
                'password_confirmation' => ['required', 'string', 'same:password'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = User::create([
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => bcrypt($attributes['password']),
            'spotify_access_token' => 'default',
            'spotify_refresh_token' => 'default',
            'harmony_token' => 'default',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
        ], 201);
    }

    /**
     * Handles the user login process.
     *
     * @param Request $request The request object containing user input.
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try{
            $attributes = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        if (!auth()->attempt($attributes)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your provided credentials could not be verified.',
            ], 401);
        }

        $user = User::where('email', $attributes['email'])->first();
        $authToken = $user->createToken('authToken')->plainTextToken;
        $user->harmony_token = $authToken;

        // Save the changes to the database
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully.',
            'harmony_token' => $authToken,
            'user_id' => $user->id
        ]);
    }
}
