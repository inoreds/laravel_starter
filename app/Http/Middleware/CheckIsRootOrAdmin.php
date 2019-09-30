<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class CheckIsRootOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role === 'root' || Auth::user()->role === 'admin') {
            return $next($request);
        } else {
            return response()->json(['error' => 'Unaothorized'], 403);
        }
    }
}
