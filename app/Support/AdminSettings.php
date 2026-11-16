<?php

namespace App\Support;

use App\Models\ContentBlock;

class AdminSettings
{
    public const DEFAULTS = [
        'currency' => 'KES',
        'timezone' => 'Africa/Nairobi',
        'notify_new_inquiries' => true,
        'notify_visit_confirmations' => true,
        'daily_summary' => false,
    ];

    public const CURRENCIES = ['KES', 'USD', 'EUR', 'EGP'];

    public const TIMEZONES = ['Africa/Nairobi', 'Africa/Cairo', 'Europe/Athens', 'UTC'];

    public static function all(): array
    {
        $saved = ContentBlock::query()->where('key', 'system.settings')->first()?->data ?? [];

        return array_replace(self::DEFAULTS, is_array($saved) ? $saved : []);
    }

    public static function save(array $settings): void
    {
        ContentBlock::updateOrCreate(['key' => 'system.settings'], ['data' => $settings]);
    }
}
