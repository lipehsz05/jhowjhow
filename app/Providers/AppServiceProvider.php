<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\UserActivity;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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

        View::composer('layouts.app', function (\Illuminate\View\View $view): void {
            $appName = config('app.name', 'Sistema JhowJhow');
            if (! Schema::hasTable('site_settings')) {
                $view->with([
                    'layoutSiteTitle' => $appName,
                    'layoutPrimaryColor' => '#0a0a0a',
                    'layoutPrimaryDark' => '#000000',
                    'layoutBodyBg' => '#f0f0f2',
                ]);

                return;
            }

            $primary = SiteSetting::get('primary_color', '#0a0a0a') ?? '#0a0a0a';
            $view->with([
                'layoutSiteTitle' => SiteSetting::get('site_title') ?: $appName,
                'layoutPrimaryColor' => $primary,
                'layoutPrimaryDark' => SiteSetting::darkenHex($primary, 0.35),
                'layoutBodyBg' => SiteSetting::get('body_bg', '#f0f0f2') ?? '#f0f0f2',
            ]);
        });
    }
}
