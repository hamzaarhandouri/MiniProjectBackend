<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Namshi\JOSE\JWT;
use Illuminate\Http\Request;

class CheckTokenMiddleware
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
        //  $token = $request->header('Authorization');
    
        // if (!$token) {
        //     return response()->json(['error' => 'Unauthorized&&'], 401);
        // }

        return $next($request);
    }
}
