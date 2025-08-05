<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('api')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (auth('api')->user()->role != 'admin'){
            return response()->json(['error'=>'Unauthorized:Only admin is authorized'],403);
        }
        return $next($request);
    }

}
