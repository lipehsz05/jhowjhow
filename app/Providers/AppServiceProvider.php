<?php

namespace App\Providers;

use App\Http\Middleware\UserActivity;
use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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

        // Timezone da sessão MySQL (falha silenciosa se DB estiver inacessível — veja DB_* no .env)
        if (config('database.default') === 'mysql' && ! config('database.skip_mysql_timezone')) {
            try {
                DB::statement("SET time_zone='-03:00'");
            } catch (\Throwable $e) {
                Log::warning('MySQL: não foi possível aplicar time_zone. Verifique DB_HOST, usuário, senha e se o IP está liberado no painel da hospedagem.', [
                    'message' => $e->getMessage(),
                ]);
            }
        }

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

            $settings = SiteSetting::query()->pluck('value', 'key');
            $primary = $settings->get('primary_color', '#0a0a0a') ?? '#0a0a0a';
            $view->with([
                'layoutSiteTitle' => $settings->get('site_title') ?: $appName,
                'layoutPrimaryColor' => $primary,
                'layoutPrimaryDark' => SiteSetting::darkenHex($primary, 0.35),
                'layoutBodyBg' => $settings->get('body_bg', '#f0f0f2') ?? '#f0f0f2',
            ]);
        });
    }
}
