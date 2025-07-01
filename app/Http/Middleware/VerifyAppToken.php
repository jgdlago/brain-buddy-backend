<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class VerifyAppToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-App-Token');
        if (!$token || !PersonalAccessToken::findToken($token)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid or missing application token'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $accessToken = PersonalAccessToken::findToken($token);
        if ($accessToken && $accessToken->tokenable) {
            auth()->login($accessToken->tokenable);
        }

        return $next($request);
    }
}
