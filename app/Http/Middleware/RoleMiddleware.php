<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        //prd($roles);
        $dbRole = Role::find(auth()->user()->role);

        if (!auth()->check() || empty($dbRole) || !in_array($dbRole->name, $roles)) {
            abort(403, "Your are Unauthorized");
        }
        return $next($request);
    }
}
