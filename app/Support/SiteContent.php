<?php

namespace App\Support;

/**
 * Structured lists for the public views, read from the CMS.
 */
class SiteContent
{
    public const GALLERY_CLASSES = [
        'feature' => 'md:col-span-8 md:row-span-2 min-h-[70vh]',
        'third' => 'md:col-span-4 min-h-[42vh]',
        'half' => 'md:col-span-6 min-h-[70vh]',
        'wide' => 'md:col-span-7 min-h-[62vh]',
        'narrow' => 'md:col-span-5 min-h-[62vh]',
        'full' => 'md:col-span-12 min-h-[78vh]',
        'small' => 'md:col-span-4 min-h-[48vh]',
    ];

    public const GALLERY_SIZES = [
        'feature' => '(min-width: 768px) 66vw, 100vw',
        'third' => '(min-width: 768px) 33vw, 100vw',
        'half' => '(min-width: 768px) 50vw, 100vw',
        'wide' => '(min-width: 768px) 58vw, 100vw',
        'narrow' => '(min-width: 768px) 42vw, 100vw',
        'full' => '100vw',
        'small' => '(min-width: 768px) 33vw, 100vw',
    ];

    public static function features(): array
    {
        return array_map(fn ($feature, $index) => array_merge($feature, [
            'n' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
            'image' => $feature['image'] ?: null,
        ]), $items = cms('home.distinctions.items', []), array_keys($items));
    }

    public static function residences(): array
    {
        return array_map(fn ($home) => array_merge($home, [
            'meta' => implode(' · ', $home['specs'] ?? []),
        ]), cms('residences.types.items', []));
    }

    public static function rooms(): array
    {
        return cms('residences.interiors.rooms', []);
    }

    public static function gallery(): array
    {
        return array_map(fn ($image) => array_merge($image, [
            'class' => self::GALLERY_CLASSES[$image['size'] ?? 'half'] ?? self::GALLERY_CLASSES['half'],
            'sizes' => self::GALLERY_SIZES[$image['size'] ?? 'half'] ?? self::GALLERY_SIZES['half'],
        ]), cms('gallery.images.items', []));
    }

    /**
     * Gallery frames with an image, grouped by chapter in chapter order.
     *
     * @return array<int, array{key: string, title: string, items: array<int, array<string, mixed>>}>
     */
    public static function galleryChapters(): array
    {
        $frames = array_values(array_filter(self::gallery(), fn ($image) => filled($image['image'] ?? null)));
        $chapters = [];

        foreach (array_keys(ContentSchema::GALLERY_CHAPTERS) as $key) {
            $items = array_values(array_filter($frames, fn ($image) => ($image['chapter'] ?? 'residences') === $key));
            if ($items) {
                $chapters[] = ['key' => $key, 'title' => (string) cms('gallery.chapters.'.$key, ''), 'items' => $items];
            }
        }

        return $chapters;
    }

    public static function portfolio(): array
    {
        return cms('about.background.portfolio', []);
    }

    public static function interests(): array
    {
        return [
            'private-viewing' => 'Book a private viewing',
            'one-bedroom' => 'One-bedroom residence',
            'two-bedroom' => 'Two-bedroom residence',
            'three-bedroom' => 'Three-bedroom residence',
            'loft' => 'Loft residences',
            'price-list' => 'Request the price list',
            'investment-pack' => 'Request the investment pack',
            'consultation' => 'Book a private consultation',
        ];
    }
}
