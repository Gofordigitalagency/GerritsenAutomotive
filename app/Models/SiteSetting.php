<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public const CACHE_KEY = 'site_settings_all';

    /**
     * Lees één waarde. Probeert eerst de DB; valt anders terug op de
     * default uit config/site_content.php; daarna op de meegegeven $default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $values = Cache::rememberForever(self::CACHE_KEY, function () {
                return static::pluck('value', 'key')->toArray();
            });
        } catch (\Throwable $e) {
            // Tabel bestaat (nog) niet of DB-fout — val terug op schema-defaults.
            $values = [];
        }

        if (array_key_exists($key, $values) && $values[$key] !== null && $values[$key] !== '') {
            return $values[$key];
        }

        return self::schemaDefault($key, $default);
    }

    /**
     * Haal de default uit het schema (config/site_content.php).
     */
    public static function schemaDefault(string $key, mixed $default = null): mixed
    {
        $schema = config('site_content', []);
        foreach ($schema as $group) {
            if (isset($group['fields'][$key]['default'])) {
                return $group['fields'][$key]['default'];
            }
        }
        return $default;
    }

    /**
     * Schrijf één waarde + invalidate cache.
     */
    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Bulk-write meerdere settings ineens.
     */
    public static function putMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Cache forceren te legen — handig na upload of seeding.
     */
    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
