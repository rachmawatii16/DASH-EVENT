<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsApplicant
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('Entered EnsureIsApplicant middleware, User Role: ' . Auth::user()->role);
    
        if (!Auth::check() || Auth::user()->role !== 'applicant') {
            \Log::info('Access denied in EnsureIsApplicant middleware.');
            return redirect('/')->with('error', 'Access denied.');
        }
    
        return $next($request);
    }
}
