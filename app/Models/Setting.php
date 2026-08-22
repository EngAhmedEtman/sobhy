<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type', 'group'];

    public static function get($key, $default = null)
    {
        try {
            return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
                $setting = self::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            });
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        try {
            $setting = self::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group]
            );

            Cache::forget('setting_' . $key);

            return $setting;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
