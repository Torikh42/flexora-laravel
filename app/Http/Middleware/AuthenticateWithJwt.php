<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateWithJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Try to get token from Authorization header first
        $token = $request->bearerToken();
        
        // If no token in header, check if it's a web session auth
        if (!$token && Auth::check()) {
            return $next($request);
        }

        // Try to authenticate with JWT
        if ($token) {
            try {
                $user = JWTAuth::setToken($token)->authenticate();
                if ($user) {
                    Auth::setUser($user);
                    return $next($request);
                }
            } catch (JWTException $e) {
                return redirect('/login')->withErrors('Invalid token');
            }
        }

        return redirect('/login');
    }
}
