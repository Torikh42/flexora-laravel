<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Get token from Authorization header, query parameter, or cookie
            $token = $request->bearerToken() 
                    ?? $request->query('token')
                    ?? $request->cookie('auth_token');
            
            if (!$token) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            // Set and authenticate token
            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate();

            if (!$user || $user->role !== 'admin') {
                abort(403, 'Unauthorized. Admin access required.');
            }

            return $next($request);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Token invalid atau expired.');
        }
    }
}
