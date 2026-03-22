<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DevToolsController extends Controller
{
    private const ARTISAN_ALLOWED = [
        'route:cache',
        'route:clear',
        'config:cache',
        'config:clear',
        'view:cache',
        'view:clear',
        'cache:clear',
        'event:cache',
        'event:clear',
        'optimize',
        'optimize:clear',
        'queue:restart',
    ];

    private const ROLES = ['administrador', 'vendedor', 'estoquista', 'dono', 'dev'];

    private static function expandHex(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        return '#'.substr($hex, 0, 6);
    }

    public function index()
    {
        return view('dev.index');
    }

    public function general()
    {
        return view('dev.general', [
            'siteTitle' => SiteSetting::get('site_title') ?? config('app.name'),
            'primaryColor' => self::expandHex(SiteSetting::get('primary_color', '#0a0a0a') ?? '#0a0a0a'),
            'bodyBg' => self::expandHex(SiteSetting::get('body_bg', '#f0f0f2') ?? '#f0f0f2'),
        ]);
    }

    public function generalUpdate(Request $request)
    {
        $validated = $request->validate([
            'site_title' => 'required|string|max:120',
            'primary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'body_bg' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ], [
            'primary_color.regex' => 'Informe uma cor principal em hexadecimal (#RGB ou #RRGGBB).',
            'body_bg.regex' => 'Informe a cor de fundo em hexadecimal (#RGB ou #RRGGBB).',
        ]);

        SiteSetting::set('site_title', $validated['site_title']);
        SiteSetting::set('primary_color', self::expandHex($validated['primary_color']));
        SiteSetting::set('body_bg', self::expandHex($validated['body_bg']));

        return redirect()->route('dev.general')
            ->with('success', 'Configurações gerais salvas. Recarregue as páginas abertas para ver o tema.');
    }

    public function users()
    {
        $users = User::query()
            ->orderByRaw("CASE nivel_acesso WHEN 'dev' THEN 0 WHEN 'dono' THEN 1 WHEN 'administrador' THEN 2 WHEN 'vendedor' THEN 3 WHEN 'estoquista' THEN 4 ELSE 5 END")
            ->orderBy('name')
            ->get();

        $devCount = User::where('nivel_acesso', 'dev')->count();

        return view('dev.users', compact('users', 'devCount'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'nivel_acesso' => 'required|in:'.implode(',', self::ROLES),
        ]);

        $newRole = $request->input('nivel_acesso');

        if ($user->isDev() && $newRole !== 'dev' && User::where('nivel_acesso', 'dev')->count() <= 1) {
            return redirect()->route('dev.users')
                ->with('error', 'Deve existir pelo menos um usuário com cargo DEV no sistema.');
        }

        $user->update(['nivel_acesso' => $newRole]);

        return redirect()->route('dev.users')
            ->with('success', 'Cargo de '.$user->name.' atualizado para '.strtoupper($newRole).'.');
    }

    public function cache()
    {
        return view('dev.cache');
    }

    public function runArtisan(Request $request)
    {
        $request->validate([
            'comando' => 'required|string|in:'.implode(',', self::ARTISAN_ALLOWED),
        ]);

        $comando = $request->input('comando');

        try {
            $exitCode = Artisan::call($comando);
            $output = trim(Artisan::output());

            $msg = $exitCode === 0
                ? 'Comando `php artisan '.$comando.'` concluído com sucesso.'
                : 'Comando finalizado com código '.$exitCode.'.';

            if ($output !== '') {
                $msg .= "\n\n".$output;
            }

            return redirect()->route('dev.cache')->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->route('dev.cache')
                ->with('error', 'Erro ao executar `'.$comando.'`: '.$e->getMessage());
        }
    }

    public function about()
    {
        try {
            Artisan::call('about');
            $about = trim(Artisan::output());
        } catch (\Throwable $e) {
            $about = 'Não foi possível executar about: '.$e->getMessage();
        }

        return view('dev.about', compact('about'));
    }

    public function migrateStatus()
    {
        try {
            Artisan::call('migrate:status');
            $output = trim(Artisan::output());
        } catch (\Throwable $e) {
            $output = 'Erro: '.$e->getMessage();
        }

        return view('dev.migrate-status', compact('output'));
    }
}
