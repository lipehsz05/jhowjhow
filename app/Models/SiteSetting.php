<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /** @var array<string, string|null> */
    protected static array $requestMemo = [];

    public static function get(string $key, ?string $default = null): ?string
    {
        if (array_key_exists($key, static::$requestMemo)) {
            $v = static::$requestMemo[$key];

            return $v !== null ? $v : $default;
        }

        $row = static::query()->where('key', $key)->first();
        static::$requestMemo[$key] = $row?->value;

        return static::$requestMemo[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        unset(static::$requestMemo[$key]);
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /** Escurece um hex (#RGB ou #RRGGBB) para usar como --primary-dark */
    public static function darkenHex(string $hex, float $amount = 0.4): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        if (strlen($hex) !== 6) {
            return '#000000';
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $r = (int) max(0, min(255, $r * (1 - $amount)));
        $g = (int) max(0, min(255, $g * (1 - $amount)));
        $b = (int) max(0, min(255, $b * (1 - $amount)));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
