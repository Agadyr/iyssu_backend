<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $accessToken = PersonalAccessToken::findToken($token);


        if ($accessToken && $accessToken->expires_at && $accessToken->expires_at < now()) {
            return response()->json([
                'message' => 'Token Expired',
                'expired_at' => $accessToken->expires_at,
            ], 401);
        }

        return $next($request);
    }
}
