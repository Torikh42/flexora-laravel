<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtAuthMiddleware
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
        try {
            $token = JWTAuth::getToken();
            
            // If no token in Authorization header, check query string or session
            if (!$token) {
                if ($request->has('token')) {
                    $token = $request->input('token');
                } elseif (session()->has('auth_token')) {
                    $token = session('auth_token');
                }
            }
            
            if (!$token) {
                return redirect()->route('login')->with('error', 'Token is absent.');
            }
            
            JWTAuth::setToken($token);
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return redirect()->route('login')->with('error', 'User not found.');
            }
        } catch (TokenExpiredException $e) {
            return redirect()->route('login')->with('error', 'Token has expired.');
        } catch (TokenInvalidException $e) {
            return redirect()->route('login')->with('error', 'Token is invalid.');
        } catch (JWTException $e) {
            return redirect()->route('login')->with('error', 'Token is absent.');
        }

        return $next($request);
    }
}
