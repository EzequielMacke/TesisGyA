<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarPermiso
{
    public function handle(Request $request, Closure $next, string $modulo)
    {
        $permisos = session('permisos', []);
        $modPerms = $permisos[$modulo] ?? null;

        if (!$modPerms || !$modPerms['ver']) {
            return $this->deny($request);
        }

        $accion = $this->resolverAccion($request);

        if ($accion && !$modPerms[$accion]) {
            return $this->deny($request);
        }

        return $next($request);
    }

    private function resolverAccion(Request $request): ?string
    {
        $method    = $request->method();
        $routeName = $request->route()?->getName() ?? '';

        $esAnular = str_contains($routeName, 'anular')
                 || str_contains($routeName, 'confirmar')
                 || str_contains($routeName, 'aprobar');

        return match (true) {
            $method === 'GET'                          => null,
            $method === 'POST'   && $esAnular          => 'anular',
            $method === 'POST'                         => 'agregar',
            $method === 'PUT'                          => 'editar',
            $method === 'PATCH'  && $esAnular          => 'anular',
            $method === 'PATCH'                        => 'editar',
            $method === 'DELETE'                       => 'anular',
            default                                    => null,
        };
    }

    private function deny(Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*') || $request->is('ajax/*')) {
            return response()->json(['error' => 'Sin permiso'], 403);
        }

        return redirect()->route('menu.index')
            ->with('error', 'No tenés permiso para realizar esa acción.');
    }
}
