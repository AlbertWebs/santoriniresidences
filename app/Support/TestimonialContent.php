<?php

namespace App\Support;

use App\Models\ContentBlock;

class TestimonialContent
{
    public static function all(): array
    {
        $data = ContentBlock::query()->where('key', 'testimonials.items')->first()?->data ?? [];
        $items = is_array($data) ? ($data['items'] ?? []) : [];

        return is_array($items) ? array_values(array_filter($items, 'is_array')) : [];
    }

    public static function published(): array
    {
        return array_values(array_filter(self::all(), fn (array $item) =>
            (bool) ($item['is_public'] ?? false)
            && filled($item['name'] ?? null)
            && filled($item['content'] ?? null)
        ));
    }

    public static function save(array $items): void
    {
        ContentBlock::updateOrCreate(['key' => 'testimonials.items'], ['data' => ['items' => $items]]);
    }
}
