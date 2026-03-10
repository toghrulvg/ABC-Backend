<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
                'name' => ['nullable', 'string'],
            ]);

            $user = User::where('email', $credentials['email'])->first();

            if ($user === null || ! Hash::check($credentials['password'], $user->password)) {
                return response()->json(['message' => 'Invalid credentials.'], 401);
            }

            $plainToken = Str::random(64);

            ApiToken::create([
                'user_id' => $user->id,
                'name' => $credentials['name'] ?? 'default',
                'token_hash' => hash('sha256', $plainToken),
                'last_used_at' => now(),
            ]);

            return response()->json([
                'token' => $plainToken,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        } catch (\Throwable $e) {
            Log::error('Login error', [
                'message' => $e->getMessage(),
            ]);

            $payload = [
                'message' => 'Login error.',
                'error' => $e->getMessage(),
            ];

            if (config('app.debug')) {
                $payload['trace'] = $e->getTraceAsString();
            }

            return response()->json($payload, 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $header = $request->header('Authorization');

            if ($header === null || ! str_starts_with($header, 'Bearer ')) {
                return response()->json(['message' => 'Token not provided.'], 400);
            }

            $token = trim(substr($header, 7));
            $hash = hash('sha256', $token);

            ApiToken::where('token_hash', $hash)->delete();

            return response()->json(['message' => 'Logged out.']);
        } catch (\Throwable $e) {
            Log::error('Logout error', [
                'message' => $e->getMessage(),
            ]);

            $payload = [
                'message' => 'Logout error.',
                'error' => $e->getMessage(),
            ];

            if (config('app.debug')) {
                $payload['trace'] = $e->getTraceAsString();
            }

            return response()->json($payload, 500);
        }
    }
}
