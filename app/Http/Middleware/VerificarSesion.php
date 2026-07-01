<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarSesion
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('user_id')) {
            if ($request->expectsJson() || $request->is('api/*') || $request->is('ajax/*')) {
                return response()->json([
                    'error'    => 'Sesión expirada',
                    'redirect' => route('login'),
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Tu sesión ha expirado. Por favor iniciá sesión nuevamente.');
        }

        return $next($request);
    }
}
