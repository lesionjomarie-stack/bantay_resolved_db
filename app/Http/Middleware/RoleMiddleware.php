<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $routeMiddleware = $request->route()?->middleware() ?? [];

        foreach ($routeMiddleware as $middleware) {
            if (! str_starts_with($middleware, 'role:')) {
                continue;
            }

            $allowedRoles = explode(',', substr($middleware, 5));
            $userRole = strtolower(Auth::user()->role ?? '');

            if (! in_array($userRole, array_map('strtolower', $allowedRoles), true)) {
                return match ($userRole) {
                    'admin' => redirect()->route('admin.dashboard'),
                    'staff' => redirect()->route('staff.dashboard'),
                    default => redirect()->route('citizen.dashboard'),
                };
            }
        }

        return $next($request);
    }
}
