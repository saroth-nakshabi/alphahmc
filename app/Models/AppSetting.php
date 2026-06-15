<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Plain (unencrypted) settings. */
    public static function get(string $key, $default = null)
    {
        $row = static::where('key', $key)->first();
        return $row ? $row->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $v = static::get($key, $default ? '1' : '0');
        return in_array((string) $v, ['1', 'true', 'on', 'yes'], true);
    }

    /** Store a secret encrypted at rest. */
    public static function setSecret(string $key, ?string $value): void
    {
        static::set($key, ($value === null || $value === '') ? null : Crypt::encryptString($value));
    }

    /** Retrieve and decrypt a secret; null if unset/undecryptable. */
    public static function getSecret(string $key): ?string
    {
        $raw = static::get($key);
        if (!$raw) {
            return null;
        }
        try {
            return Crypt::decryptString($raw);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
