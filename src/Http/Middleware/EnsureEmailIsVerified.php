<?php

namespace Modules\DesaModuleTemplate\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(desa_module_template_meta('snake').'_web');

        if (! $user || ! $user->hasVerifiedEmail()) {
            return redirect()->route(desa_module_template_meta('kebab').'.verification.notice');
        }

        return $next($request);
    }
}

