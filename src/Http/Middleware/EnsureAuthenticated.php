<?php

namespace Modules\DesaModuleTemplate\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null) // temp
    {
        $guard ??= desa_module_template_meta('snake').'_web';

        if (!Auth::guard($guard)->check()) {
            return redirect()->route(desa_module_template_meta('kebab').'.login');
        }

        return $next($request);
    }
}
