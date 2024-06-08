<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_super_admin) {
            return $next($request);
        }

        return redirect('home')->with('error', 'You do not have access to this resource.');
    }
}
