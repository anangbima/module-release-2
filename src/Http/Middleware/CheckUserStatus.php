<?php

namespace Modules\DesaModuleTemplate\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next,  $guard = 'desa_module_template_web'): Response
    {
        $user = Auth::guard($guard)->user();

        if (! $user || !$user->isActive()) {
            Auth::guard($guard)->logout();

            $request->session()->invalidate(); // untuk session web
            $request->session()->regenerateToken(); // optional, demi keamanan

            abort(403, 'Your account is suspend or inactive. Please contact support.');
        }

        return $next($request);
    }
}
