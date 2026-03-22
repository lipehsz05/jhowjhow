<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDev
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isDev()) {
            abort(403, 'Acesso restrito a contas desenvolvedor (DEV).');
        }

        return $next($request);
    }
}
