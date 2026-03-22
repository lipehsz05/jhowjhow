<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se já estiver na página de login ou logout, deixe passar para evitar loops
        if ($request->routeIs('login') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Favicon: navegadores pedem sem cookie de sessão; não redirecionar para login
        if ($request->is('favicon.ico')) {
            return $next($request);
        }
        
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            // Se não estiver autenticado, redirecionar para login
            return redirect()->route('login');
        }
        
        // Se o usuário está tentando acessar o dashboard
        if ($request->routeIs('dashboard')) {
            // Redirecionar vendedor para a página de vendas
            if (Auth::user()->nivel_acesso === 'vendedor') {
                return redirect()->route('sales.index');
            }
            
            // Redirecionar estoquista para a página de estoque
            if (Auth::user()->nivel_acesso === 'estoquista') {
                return redirect()->route('inventory.index');
            }
        }
        
        return $next($request);
    }
}
