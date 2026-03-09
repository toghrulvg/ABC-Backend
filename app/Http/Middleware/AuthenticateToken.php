<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization');
        $token = $this->extractToken($header);

        if ($token === null) {
            return $this->unauthenticated();
        }

        $hash = hash('sha256', $token);
        $apiToken = ApiToken::where('token_hash', $hash)->first();

        if ($apiToken === null) {
            return $this->unauthenticated();
        }

        if ($apiToken->expires_at !== null && now()->greaterThan($apiToken->expires_at)) {
            return $this->unauthenticated();
        }

        $user = $apiToken->user;

        if ($user === null) {
            return $this->unauthenticated();
        }

        $apiToken->forceFill(['last_used_at' => now()])->save();

        Auth::setUser($user);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }

    private function extractToken(?string $header): ?string
    {
        if ($header === null) {
            return null;
        }

        if (str_starts_with($header, 'Bearer ')) {
            return trim(substr($header, 7));
        }

        return null;
    }

    private function unauthenticated(): JsonResponse
    {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
