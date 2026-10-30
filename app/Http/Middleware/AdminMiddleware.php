<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Permite el paso solo a usuarios autenticados con admin = 1.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->admin) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
