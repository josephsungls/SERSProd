<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request)
        // -> header('Access-Control-Allow-Origin', 'http://18.142.17.108:5000')
        -> header('Access-Control-Allow-Origin', 'http://localhost:8080')
        -> header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        -> header('Access-Control-Allow-Headers', 'Content-Type, Set-Cookie, x-xsrf-token')
        -> header('Access-Control-Allow-Credentials', 'true');
        // -> header('');
    }
}
