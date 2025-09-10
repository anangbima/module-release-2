<?php

namespace Modules\ModuleRelease2\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(module_release_2_meta('snake').'_web');

        if (! $user || ! $user->hasVerifiedEmail()) {
            return redirect()->route(module_release_2_meta('kebab').'.verification.notice');
        }

        return $next($request);
    }
}

