<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckStudentRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if ( (Auth::check() && Auth::user()->role !== 1) && (Auth::check() && Auth::user()->role !== 0)) {
            return $next($request);
        }
        return redirect('/index-lecturer');
    }

}
