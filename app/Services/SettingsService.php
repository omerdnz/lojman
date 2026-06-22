<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public const DEFAULT_ROOM_CAPACITY = 'default_room_capacity';

    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = Setting::query()->where('key', $key)->first();

            if (! $setting) {
                return $default;
            }

            $value = $setting->value;

            return is_array($value) && array_key_exists('value', $value)
                ? $value['value']
                : $value;
        });
    }

    public function getInt(string $key, int $default = 0): int
    {
        return (int) $this->get($key, $default);
    }

    public function set(string $key, mixed $value, ?string $label = null, ?string $description = null, string $group = 'general'): Setting
    {
        $setting = Setting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => ['value' => $value],
                'label' => $label,
                'description' => $description,
                'group' => $group,
            ]
        );

        Cache::forget("setting.{$key}");

        return $setting;
    }

    public function defaultRoomCapacity(): int
    {
        return max(1, $this->getInt(self::DEFAULT_ROOM_CAPACITY, 4));
    }
}
