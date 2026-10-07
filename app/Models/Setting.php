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
                if ($key === 'phone' || $key === 'company_phone') {
                    $setting = self::whereIn('key', ['company_phone', 'phone'])
                        ->whereNotNull('value')
                        ->where('value', '!=', '')
                        ->orderByRaw("CASE WHEN key = 'company_phone' THEN 1 ELSE 2 END")
                        ->first();
                    return $setting ? $setting->value : $default;
                }

                if ($key === 'address' || $key === 'company_address') {
                    $setting = self::whereIn('key', ['company_address', 'address'])
                        ->whereNotNull('value')
                        ->where('value', '!=', '')
                        ->orderByRaw("CASE WHEN key = 'company_address' THEN 1 ELSE 2 END")
                        ->first();
                    return $setting ? $setting->value : $default;
                }

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

            if ($key === 'phone' || $key === 'company_phone') {
                Cache::forget('setting_phone');
                Cache::forget('setting_company_phone');
            }
            if ($key === 'address' || $key === 'company_address') {
                Cache::forget('setting_address');
                Cache::forget('setting_company_address');
            }

            return $setting;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
