<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherOrAdminMiddleware
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

        if (!in_array(auth('api')->user()->role, ['admin', 'teacher'])) {
            return response()->json(['error' => 'Unauthorized: Only admin or teacher can access students'], 403);
        }

        return $next($request);
    }
}
