<?php

namespace App\Support;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

/**
 * Resized WebP copies of library images, stored in a `_w` folder beside the original
 * (media/_w/kitchen-800.webp), so pages can offer the browser a srcset instead of
 * sending the full-size render to every screen.
 */
class ResponsiveImage
{
    public const WIDTHS = [400, 800, 1200, 1600, 2400];

    public const QUALITY = 78;

    public const DIRECTORY = '_w';

    private const EXTENSIONS = ['webp', 'jpg', 'jpeg', 'png'];

    /** @var array<string, array<int, string>> */
    private static array $variants = [];

    /** @var array<string, array{0:int,1:int}|null> */
    private static array $dimensions = [];

    public static function supports(?string $path): bool
    {
        return $path
            && ! preg_match('#^(https?:)?//#i', $path)
            && in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), self::EXTENSIONS, true)
            && ! str_contains($path, '/'.self::DIRECTORY.'/');
    }

    public static function variantPath(string $path, int $width): string
    {
        $path = ltrim($path, '/');
        $directory = dirname($path);

        return ($directory === '.' ? '' : $directory.'/').self::DIRECTORY.'/'.self::stem($path).'-'.$width.'.webp';
    }

    /**
     * Variant file stem. Non-WebP originals keep their extension in the name so that
     * logo.png and logo.jpg in the same folder do not share variants.
     */
    private static function stem(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return pathinfo($path, PATHINFO_FILENAME).($extension === 'webp' ? '' : '-'.$extension);
    }

    /**
     * Absolute file path for a public-relative path. Uploads are resolved on the public
     * disk directly so this works whether or not the storage link is in place.
     */
    public static function absolute(string $path): string
    {
        $path = ltrim($path, '/');

        return str_starts_with($path, 'storage/')
            ? storage_path('app/public/'.substr($path, strlen('storage/')))
            : public_path($path);
    }

    /**
     * @return array{0:int,1:int}|null
     */
    public static function dimensions(?string $path): ?array
    {
        if (! self::supports($path)) {
            return null;
        }

        if (! array_key_exists($path, self::$dimensions)) {
            $size = @getimagesize(self::absolute($path));

            if (! $size && config('filesystems.media_disk') === 's3') {
                $media = Media::query()->where('path', $path)->first(['width', 'height']);
                $size = $media?->width && $media?->height ? [$media->width, $media->height] : false;
            }

            self::$dimensions[$path] = $size ? [(int) $size[0], (int) $size[1]] : null;
        }

        return self::$dimensions[$path];
    }

    /**
     * Existing variants keyed by width, smallest first.
     *
     * @return array<int, string>
     */
    public static function variants(?string $path): array
    {
        if (! self::supports($path)) {
            return [];
        }

        if (config('filesystems.media_disk') === 's3') {
            $dimensions = self::dimensions($path);
            if (! $dimensions || ! function_exists('imagewebp') || $dimensions[0] * $dimensions[1] > 60_000_000) {
                return [];
            }
        }

        if (! array_key_exists($path, self::$variants)) {
            $found = [];
            foreach (self::widthsFor(self::dimensions($path)[0] ?? 0) as $width) {
                $variant = self::variantPath($path, $width);
                if (config('filesystems.media_disk') === 's3' || is_file(self::absolute($variant))) {
                    $found[$width] = $variant;
                }
            }
            self::$variants[$path] = $found;
        }

        return self::$variants[$path];
    }

    public static function srcset(?string $path): string
    {
        $variants = self::variants($path);

        if (! $variants) {
            return '';
        }

        return implode(', ', array_map(fn ($variant, $width) => cms_asset($variant).' '.$width.'w', $variants, array_keys($variants)));
    }

    /**
     * Target widths for an original: the standard steps below it, plus a re-encoded copy at
     * full width when the original is narrower than the largest step.
     *
     * @return array<int, int>
     */
    public static function widthsFor(int $originalWidth): array
    {
        $widths = array_filter(self::WIDTHS, fn ($width) => $width < $originalWidth);

        if ($originalWidth > 0 && $originalWidth <= max(self::WIDTHS)) {
            $widths[] = $originalWidth;
        }

        return array_values(array_unique($widths));
    }

    /**
     * URL of the smallest variant at least $width wide, falling back to the original.
     */
    public static function url(?string $path, int $width = 1600): string
    {
        $variants = self::variants($path);

        foreach ($variants as $variantWidth => $variant) {
            if ($variantWidth >= $width) {
                return cms_asset($variant);
            }
        }

        return $variants ? cms_asset(end($variants)) : cms_asset($path);
    }

    /**
     * Write any missing variants for a public-relative image path. Returns how many were created.
     */
    public static function generate(string $path, bool $force = false): int
    {
        if (! self::supports($path) || ! function_exists('imagewebp')) {
            return 0;
        }

        $source = self::absolute($path);
        $cloud = config('filesystems.media_disk') === 's3';
        $sourceContents = null;

        if ($cloud && ! is_file($source)) {
            $sourceContents = Storage::disk('s3')->get(self::storageKey($path));
        }

        $size = $sourceContents !== null ? @getimagesizefromstring($sourceContents) : @getimagesize($source);

        if (! $size) {
            return 0;
        }

        [$width, $height] = $size;
        $targets = array_filter(self::widthsFor($width), fn ($target) => $force
            || ! is_file(self::absolute(self::variantPath($path, $target))));

        if (! $targets) {
            return 0;
        }

        $previousLimit = ini_get('memory_limit');
        ini_set('memory_limit', '1024M');

        try {
            $image = match ($size['mime'] ?? '') {
                'image/webp' => $sourceContents !== null ? @imagecreatefromstring($sourceContents) : @imagecreatefromwebp($source),
                'image/jpeg' => $sourceContents !== null ? @imagecreatefromstring($sourceContents) : @imagecreatefromjpeg($source),
                'image/png' => $sourceContents !== null ? @imagecreatefromstring($sourceContents) : @imagecreatefrompng($source),
                default => false,
            };

            if (! $image) {
                return 0;
            }

            imagepalettetotruecolor($image);
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $created = 0;

            foreach ($targets as $target) {
                $resized = imagescale($image, $target, (int) round($height * $target / $width), IMG_BICUBIC);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);

                $variantPath = self::variantPath($path, $target);
                $destination = self::absolute($variantPath);

                if ($cloud) {
                    ob_start();
                    imagewebp($resized, null, self::QUALITY);
                    $encoded = ob_get_clean();
                    Storage::disk('s3')->put(self::storageKey($variantPath), $encoded, [
                        'ContentType' => 'image/webp',
                        'CacheControl' => 'public, max-age=31536000, immutable',
                    ]);
                } else {
                    if (! is_dir(dirname($destination))) {
                        mkdir(dirname($destination), 0755, true);
                    }

                    imagewebp($resized, $destination, self::QUALITY);
                }
                imagedestroy($resized);
                $created++;
            }

            imagedestroy($image);
        } finally {
            ini_set('memory_limit', $previousLimit);
        }

        unset(self::$variants[$path]);

        return $created;
    }

    public static function delete(string $path): void
    {
        if (config('filesystems.media_disk') === 's3') {
            $disk = Storage::disk('s3');
            $directory = self::storageKey(dirname(self::variantPath($path, 1)));
            $stem = self::stem($path);

            foreach ($disk->files($directory) as $variant) {
                if (preg_match('/^'.preg_quote($stem, '/').'-\\d+\\.webp$/', basename($variant))) {
                    $disk->delete(self::storageKey($variant));
                }
            }
            unset(self::$variants[$path], self::$dimensions[$path]);

            return;
        }

        $directory = dirname(self::absolute(self::variantPath($path, 1)));
        $pattern = '/^'.preg_quote(self::stem($path), '/').'-\d+\.webp$/';

        foreach (glob($directory.'/*.webp') ?: [] as $file) {
            if (preg_match($pattern, basename($file))) {
                @unlink($file);
            }
        }

        unset(self::$variants[$path], self::$dimensions[$path]);
    }

    private static function storageKey(string $path): string
    {
        $path = ltrim($path, '/');

        return str_starts_with($path, 'storage/') ? substr($path, strlen('storage/')) : $path;
    }
}
