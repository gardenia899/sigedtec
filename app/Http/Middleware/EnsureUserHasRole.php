<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Control de acceso por rol (RBAC) — SDD §8.5.
 *
 * Uso: Route::middleware(['auth', 'role:admin'])
 * La autorización se aplica en el servidor: ocultar botones no basta.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null || ! in_array($user->role, $roles, true)) {
            abort(403, 'No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}