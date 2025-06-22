<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Google_Client;

class AuthController extends Controller
{
    //function for register
    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:5|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Return a response
        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => $user,
        ], 201);
    }

    function loginGoogle(Request $request)
    {
        // Validate the request data
        $request->validate([
            'id_token' => 'required|string|',
        ]);

        // get the id token from the request
        $idToken = $request->id_token;
        $client = new Google_Client(['client_id' =>env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($idToken);

        // Check if the payload is valid
        if ($payload) {
            $user = User::where('email', $payload['email'])->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ],
                ], 200);
            } else {
                $user = User::create([
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'password' => Hash::make($payload['sub']), // Generate a random password
                ]);
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status' => 'success',
                    'message' => 'User created successfully',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ],
                ], 201);
        }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid ID token',
            ], 401);
        }
    }

    // Function for login
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // check if the user exists
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$user && !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Create a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a response
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
    }

    // Function for logout
    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->currentAccessToken()->delete();
        // Return a response
        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful',
        ], 200);
    }
}
