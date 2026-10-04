<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateBroadcastUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token_device');
        $cookieUserId = $request->cookie('user_id');

        if (!$token || !$cookieUserId) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (JWTException) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!$user || (string) $user->getKey() !== (string) $cookieUserId) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $request->setUserResolver(fn ($guard = null) => $user);
        $request->attributes->set('user_id', $user->getKey());

        return $next($request);
    }
}