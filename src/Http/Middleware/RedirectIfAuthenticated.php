<?php

namespace Modules\ModuleRelease2\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string $guard = 'module_release_2_web')
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();

            // Redirect berdasarkan role
            if ($user->hasRole('admin')) {
                return redirect()->route(module_release_2_meta('kebab').'.admin.index');
            }

            return redirect()->route(module_release_2_meta('kebab').'.user.index');
        }

        return $next($request);
    }
}
