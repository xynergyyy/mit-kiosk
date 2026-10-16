<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KioskSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting by key.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, $value): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings as key-value array with defaults.
     */
    public static function getAllSettings(): array
    {
        $defaults = [
            'service_hours_start' => '08:00',
            'service_hours_end' => '17:00',
            'service_days' => 'Monday - Friday',
            'kiosk_title' => 'Campus Guide - MIT',
            'kiosk_status' => 'online', // online, maintenance, offline
            'contact_email' => 'support@mit-maps.com',
            'auto_close_time' => '18:00',
        ];

        $stored = static::pluck('value', 'key')->toArray();
        return array_merge($defaults, $stored);
    }
}
