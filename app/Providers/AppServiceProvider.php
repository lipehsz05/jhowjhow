<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\UserActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Definir o timezone para Brasília em toda a aplicação
        date_default_timezone_set('America/Sao_Paulo');
        
        // Configurar o Carbon para usar o mesmo timezone
        Date::setLocale('pt_BR');
        
        // Configurar o timezone no MySQL
        DB::statement("SET time_zone='-03:00'");
        
        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('auth', UserActivity::class);
        
        // Registrar o middleware de redirecionamento baseado em papel
        $router->pushMiddlewareToGroup('web', \App\Http\Middleware\RoleRedirect::class);
    }
}
