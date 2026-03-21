<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Atualiza a marca de última atividade e status online
            $user->update([
                'last_activity' => now(),
                'is_online' => true
            ]);
            
            // Armazena o status online do usuário em cache por 5 minutos
            Cache::put('user-is-online-' . $user->id, true, Carbon::now()->addMinutes(5));
        }
        
        return $next($request);
    }
}
