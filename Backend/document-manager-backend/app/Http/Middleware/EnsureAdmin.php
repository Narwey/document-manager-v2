<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() &&  ($request->user()->role === 'admin') || ($request->user()->role === 'manager')) {
            return $next($request);
        }

        return response()->json(['message' => 'Access denied. you do not have permissions.'], 403);
    }
}
