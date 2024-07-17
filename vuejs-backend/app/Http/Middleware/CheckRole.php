<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {        
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $user = Auth::user();
            
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }
        return response()->json(['error' => 'Forbidden'], 403);
    }
    
}
