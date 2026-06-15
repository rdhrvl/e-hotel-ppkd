<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePinIsSet
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->hasPinSet()) {
            return redirect()->route('pin.create')->with('error', 'You must set up a PIN code before accessing the application.');
        }

        return $next($request);
    }
}
