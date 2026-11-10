<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Authenticate student and generate token
     */
    public function login(Request $request)
    {
        // 1. Validate the request. We allow login via email or NIS.
        $request->validate([
            'login' => 'required|string', // can be email or nis
            'password' => 'required|string',
        ]);

        // 2. Find the user by email OR nis
        $user = User::where('email', $request->login)
                    ->orWhere('nis', $request->login)
                    ->first();

        // 3. Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials. Please check your NIS/Email and password.'
            ], 401);
        }

        // 4. Generate the API token
        $token = $user->createToken('vue-frontend-token')->plainTextToken;

        // 5. Return the token and user data to the frontend
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Log out the student (Revoke the token)
     */
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }
}