<?php

if (! function_exists('app_public_url')) {
    /**
     * URL para arquivo em public/, alinhada ao domínio e subpasta da requisição atual.
     * Útil em hospedagem compartilhada quando APP_URL no .env não bate com o site real.
     */
    function app_public_url(string $path): string
    {
        $path = ltrim($path, '/');

        if (! app()->runningInConsole() && request()) {
            return rtrim(request()->root(), '/').'/'.$path;
        }

        $base = rtrim((string) config('app.url'), '/');

        return ($base !== '' ? $base : '').'/'.$path;
    }
}
