<?php

namespace App\Support;

use App\Models\ContentBlock;
use Illuminate\Support\Facades\Cache;

class Cms
{
    public const CACHE_KEY = 'cms.content-blocks';

    private ?array $stored = null;

    /** @var array<string, array<string, mixed>> */
    private array $resolved = [];

    /**
     * Read a value by "page.section.field" path, with optional deeper keys (e.g. "home.hero.primary.url").
     */
    public function get(string $path, mixed $default = null): mixed
    {
        $segments = explode('.', $path, 3);

        if (count($segments) < 2) {
            return $default;
        }

        $section = $this->section($segments[0], $segments[1]);

        if (! isset($segments[2])) {
            return $section;
        }

        return data_get($section, $segments[2], $default);
    }

    /**
     * Every field of a section, stored values layered over schema defaults.
     *
     * @return array<string, mixed>
     */
    public function section(string $page, string $section): array
    {
        $key = $page.'.'.$section;

        if (isset($this->resolved[$key])) {
            return $this->resolved[$key];
        }

        $schema = ContentSchema::section($page, $section);
        $stored = $this->stored()[$key] ?? [];
        $values = [];

        foreach ($schema['fields'] ?? [] as $name => $field) {
            $values[$name] = $this->resolve($field, $stored, $name);
        }

        return $this->resolved[$key] = $values;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function page(string $page): array
    {
        $values = [];

        foreach (array_keys(ContentSchema::page($page)['sections'] ?? []) as $section) {
            $values[$section] = $this->section($page, $section);
        }

        return $values;
    }

    /**
     * Keys of sections on a page that hold saved (non-default) content.
     *
     * @return list<string>
     */
    public function customisedSections(string $page): array
    {
        return array_values(array_map(
            fn ($key) => substr($key, strlen($page) + 1),
            array_filter(array_keys($this->stored()), fn ($key) => str_starts_with($key, $page.'.')),
        ));
    }

    /**
     * Sanitise and store every section of a page from editor input.
     *
     * @param  array<string, mixed>  $input
     */
    public function save(string $page, array $input): void
    {
        foreach (ContentSchema::page($page)['sections'] ?? [] as $section => $schema) {
            if (! array_key_exists($section, $input)) {
                continue;
            }

            $data = [];
            foreach ($schema['fields'] as $name => $field) {
                $data[$name] = $this->sanitise($field, $input[$section][$name] ?? null);
            }

            ContentBlock::updateOrCreate(['key' => $page.'.'.$section], ['data' => $data]);
        }

        $this->flush();
    }

    public function resetSection(string $page, string $section): void
    {
        ContentBlock::where('key', $page.'.'.$section)->delete();
        $this->flush();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        $this->stored = null;
        $this->resolved = [];
    }

    /**
     * Public URL for a stored media path (relative to /public) or an absolute URL.
     */
    public function asset(?string $path): string
    {
        if (! $path) {
            return '';
        }

        if (preg_match('#^(https?:)?//#i', $path)) {
            return $path;
        }

        $encoded = implode('/', array_map('rawurlencode', explode('/', ltrim($path, '/'))));

        return asset($encoded);
    }

    /**
     * Resolve an editor-entered link: site paths, in-page anchors, or external URLs.
     */
    public function href(?string $url): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return '#';
        }

        if (str_starts_with($url, '#') || preg_match('#^(https?:|mailto:|tel:|//)#i', $url)) {
            return $url;
        }

        return url('/'.ltrim($url, '/'));
    }

    public function isExternal(?string $url): bool
    {
        return (bool) preg_match('#^(https?:)?//#i', (string) $url)
            && ! str_starts_with((string) $url, url('/'));
    }

    private function stored(): array
    {
        return $this->stored ??= Cache::rememberForever(self::CACHE_KEY, fn () => ContentBlock::query()
            ->get(['key', 'data'])
            ->mapWithKeys(fn (ContentBlock $block) => [$block->key => $block->data ?? []])
            ->all());
    }

    private function resolve(array $field, array $stored, string $name): mixed
    {
        $default = $field['default'] ?? null;

        if (! array_key_exists($name, $stored)) {
            return $default;
        }

        $value = $stored[$name];

        return match ($field['type']) {
            'image', 'video' => $value === null || $value === '' ? $default : $value,
            'link' => is_array($value) ? array_merge(['label' => '', 'url' => ''], $value) : $default,
            'lines', 'repeater' => is_array($value) ? $value : $default,
            default => $value ?? $default,
        };
    }

    private function sanitise(array $field, mixed $value): mixed
    {
        return match ($field['type']) {
            'text' => $this->string($value, 400),
            'textarea' => $this->string($value, 5000, true),
            'image', 'video' => $this->mediaPath($value),
            'select' => array_key_exists((string) $value, $field['options']) ? (string) $value : $field['default'],
            'link' => [
                'label' => $this->string(is_array($value) ? ($value['label'] ?? '') : '', 200),
                'url' => $this->string(is_array($value) ? ($value['url'] ?? '') : '', 1000),
            ],
            'lines' => $this->lines($value),
            'repeater' => $this->repeater($field, $value),
            default => null,
        };
    }

    private function string(mixed $value, int $max, bool $multiline = false): string
    {
        if (! is_scalar($value)) {
            return '';
        }

        $value = str_replace(["\r\n", "\r"], "\n", (string) $value);
        $value = $multiline ? trim($value) : trim(preg_replace('/\s*\n\s*/', ' ', $value));

        return mb_substr($value, 0, $max);
    }

    private function mediaPath(mixed $value): string
    {
        $value = $this->string($value, 1000);

        if ($value === '' || str_contains($value, '..')) {
            return '';
        }

        if (preg_match('#^https?://#i', $value)) {
            return $value;
        }

        return ltrim(preg_replace('#^/+#', '', $value), '/');
    }

    /**
     * @return list<string>
     */
    private function lines(mixed $value): array
    {
        $items = is_array($value) ? $value : preg_split('/\r\n|\r|\n/', (string) $value);

        return array_values(array_filter(
            array_map(fn ($line) => $this->string($line, 400), $items),
            fn ($line) => $line !== '',
        ));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function repeater(array $field, mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $items = [];
        foreach ($value as $item) {
            if (! is_array($item)) {
                continue;
            }

            $clean = [];
            $filled = false;
            foreach ($field['fields'] as $name => $subfield) {
                $clean[$name] = $this->sanitise($subfield, $item[$name] ?? null);

                if ($subfield['type'] !== 'select') {
                    $filled = $filled || (is_array($clean[$name]) ? array_filter($clean[$name]) !== [] : $clean[$name] !== '');
                }
            }

            if ($filled) {
                $items[] = $clean;
            }

            if (count($items) >= 60) {
                break;
            }
        }

        return $items;
    }
}
