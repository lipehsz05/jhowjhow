<?php

if (! function_exists('app_public_url')) {
    /**
     * URL para arquivo dentro de public/ (logos, js, etc.).
     *
     * 1) ASSET_URL no .env — base fixa (ex.: https://dominio.com/public).
     * 2) Na web: host + getBasePath() — em muitas hospedagens inclui /public automaticamente.
     * 3) PUBLIC_PATH_PREFIX=public — se o item 2 não trouxer /public e os arquivos estiverem em /public/... na URL.
     */
    function app_public_url(string $path): string
    {
        $path = ltrim($path, '/');

        if ($configured = config('app.asset_url')) {
            return rtrim($configured, '/').'/'.$path;
        }

        $prefix = trim((string) config('app.public_path_prefix'), '/');
        $appendPrefix = static function (string $base) use ($prefix): string {
            $base = rtrim($base, '/');
            if ($prefix === '' || str_ends_with($base, '/'.$prefix)) {
                return $base;
            }

            return $base.'/'.$prefix;
        };

        if (! app()->runningInConsole() && request()) {
            $base = rtrim(request()->getSchemeAndHttpHost().request()->getBasePath(), '/');
            $base = $appendPrefix($base);

            return $base.'/'.$path;
        }

        $base = $appendPrefix(rtrim((string) config('app.url'), '/'));

        return ($base !== '' ? $base : '').'/'.$path;
    }
}
