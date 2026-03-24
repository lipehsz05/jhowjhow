<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserActivity
{
    /**
     * Evita UPDATE em users a cada requisição (pesado com DB remoto após idle).
     * Mantém presença "online" no cache em toda navegação.
     */
    private const DB_UPDATE_THROTTLE_MINUTES = 2;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            Cache::put('user-is-online-' . $user->id, true, now()->addMinutes(5));

            $flushKey = 'user-activity-db-flush-' . $user->id;
            if (! Cache::has($flushKey)) {
                $user->update([
                    'last_activity' => now(),
                    'is_online' => true,
                ]);
                Cache::put($flushKey, true, now()->addMinutes(self::DB_UPDATE_THROTTLE_MINUTES));
            }
        }

        return $next($request);
    }
}
