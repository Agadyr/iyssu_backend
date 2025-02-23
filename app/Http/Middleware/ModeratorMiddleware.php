<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModeratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() && auth()->user()->isModerator()) {
            return $next($request);
        }
        return \response()->json(['message' => 'This user does not have moderator permisson'], 403);

    }
}
