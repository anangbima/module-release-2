<?php

namespace Modules\ModuleRelease2\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null) // temp
    {
        $guard ??= module_release_2_meta('snake').'_web';

        if (!Auth::guard($guard)->check()) {
            return redirect()->route(module_release_2_meta('kebab').'.login');
        }

        return $next($request);
    }
}
