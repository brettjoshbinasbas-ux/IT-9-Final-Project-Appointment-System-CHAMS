<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if(!$user){
            abort(401, 'Unauthorized. Please Log in.');
        }
        
        if(!$user->isAdmin()){
            abort(403,'Access denied. Admins only.');
        }

        return $next($request);
    }
}
