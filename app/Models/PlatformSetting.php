<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $table = 'platform_settings';
    protected $fillable = ['key','value'];

    public static function get(string $key, $default = null)
    {
        try {
            $item = static::where('key',$key)->first();
            return $item ? $item->value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $key, $value): void
    {
        try {
            static::updateOrCreate(['key'=>$key], ['value'=>$value]);
        } catch (\Throwable $e) {
        }
    }
}
