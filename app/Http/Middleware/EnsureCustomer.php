<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role !== 'customer') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
