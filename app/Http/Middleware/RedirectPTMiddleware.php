<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectPTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'pt') {
            // Jika PT mencoba akses dashboard, redirect ke member mereka
            if ($request->routeIs('dashboard') || $request->routeIs('home')) {
                return redirect()->route('pt-members.index');
            }
        }

        return $next($request);
    }
}