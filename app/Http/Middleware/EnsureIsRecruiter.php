<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnsureIsRecruiter
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'recruiter') {
            // Check if this is the first time logging in as recruiter
            if (!Session::has('recruiter_welcomed')) {
                Session::put('recruiter_welcomed', true);
                Session::flash('success', 'Welcome! You are now logged in as a recruiter. You can create and manage events.');
            }
            return $next($request);
        }

        return redirect('/')->with('error', 'You do not have access to this section.');
    }
}
