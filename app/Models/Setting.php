<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Simple key/value store for admin-editable settings (e.g. booking hours).
 * Reads are cached forever and invalidated on write.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private static function cacheKey(string $key): string
    {
        return "setting:{$key}";
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = Cache::rememberForever(
            self::cacheKey($key),
            fn () => self::query()->where('key', $key)->value('value'),
        );

        return $value ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value],
        );

        Cache::forget(self::cacheKey($key));
    }
}
