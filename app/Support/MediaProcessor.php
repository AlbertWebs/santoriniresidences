<?php

namespace App\Support;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Turns an assembled upload into a library file: images are resized and converted
 * to WebP with GD, MP4 films are stored as they are.
 */
class MediaProcessor
{
    public const IMAGE_TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    public const VIDEO_TYPES = ['video/mp4' => 'mp4'];

    public const MAX_IMAGE_BYTES = 25 * 1024 * 1024;

    public const MAX_VIDEO_BYTES = 600 * 1024 * 1024;

    public const MAX_EDGE = 2560;

    public function store(string $sourcePath, string $originalName): Media
    {
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($sourcePath) ?: '';
        $size = filesize($sourcePath) ?: 0;

        if (isset(self::IMAGE_TYPES[$mime])) {
            if ($size > self::MAX_IMAGE_BYTES) {
                throw new RuntimeException('Images must be 25 MB or smaller.');
            }

            $media = $this->storeImage($sourcePath, $mime, $originalName);
            ResponsiveImage::generate($media->path);

            return $media;
        }

        if (isset(self::VIDEO_TYPES[$mime])) {
            if ($size > self::MAX_VIDEO_BYTES) {
                throw new RuntimeException('Films must be 600 MB or smaller.');
            }

            return $this->storeVideo($sourcePath, $originalName, $size);
        }

        throw new RuntimeException('Only JPG, PNG, WebP images and MP4 films can be uploaded.');
    }

    private function storeImage(string $sourcePath, string $mime, string $originalName): Media
    {
        [$width, $height] = getimagesize($sourcePath) ?: [0, 0];

        if (! $width || ! $height) {
            throw new RuntimeException('This image could not be read.');
        }

        $disk = Storage::disk('public');
        $disk->makeDirectory(Media::UPLOAD_DIRECTORY);

        if (! function_exists('imagewebp') || $width * $height > 60_000_000) {
            $name = Media::UPLOAD_DIRECTORY.'/'.Str::random(32).'.'.self::IMAGE_TYPES[$mime];
            $disk->put($name, fopen($sourcePath, 'rb'));

            return $this->record($name, $originalName, $mime, $width, $height);
        }

        $previousLimit = ini_get('memory_limit');
        ini_set('memory_limit', '1024M');

        try {
            $image = match ($mime) {
                'image/jpeg' => imagecreatefromjpeg($sourcePath),
                'image/png' => imagecreatefrompng($sourcePath),
                'image/webp' => imagecreatefromwebp($sourcePath),
            };

            if (! $image) {
                throw new RuntimeException('This image could not be read.');
            }

            if ($mime === 'image/jpeg') {
                $image = $this->orient($image, $sourcePath);
            }

            $image = $this->fit($image);
            imagepalettetotruecolor($image);
            imagealphablending($image, false);
            imagesavealpha($image, true);

            $name = Media::UPLOAD_DIRECTORY.'/'.Str::random(32).'.webp';
            $target = $disk->path($name);
            imagewebp($image, $target, 84);

            $finalWidth = imagesx($image);
            $finalHeight = imagesy($image);
            imagedestroy($image);
        } finally {
            ini_set('memory_limit', $previousLimit);
        }

        return $this->record($name, $originalName, 'image/webp', $finalWidth, $finalHeight);
    }

    private function storeVideo(string $sourcePath, string $originalName, int $size): Media
    {
        $disk = Storage::disk('public');
        $disk->makeDirectory(Media::UPLOAD_DIRECTORY);
        $name = Media::UPLOAD_DIRECTORY.'/'.Str::random(32).'.mp4';

        if (! @rename($sourcePath, $disk->path($name))) {
            $stream = fopen($sourcePath, 'rb');
            $disk->writeStream($name, $stream);
            is_resource($stream) && fclose($stream);
        }

        return $this->record($name, $originalName, 'video/mp4', null, null);
    }

    private function record(string $name, string $originalName, string $mime, ?int $width, ?int $height): Media
    {
        $path = 'storage/'.$name;

        return Media::create([
            'path' => $path,
            'original_name' => Str::limit($originalName, 180, ''),
            'mime' => $mime,
            'size' => filesize(Storage::disk('public')->path($name)) ?: 0,
            'width' => $width,
            'height' => $height,
            'alt' => Str::of(pathinfo($originalName, PATHINFO_FILENAME))->replaceMatches('/[_\-]+/', ' ')->squish()->ucfirst()->limit(160, '')->toString(),
        ]);
    }

    private function fit(\GdImage $image): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $scale = min(1, self::MAX_EDGE / max($width, $height));

        if ($scale >= 1) {
            return $image;
        }

        $resized = imagescale($image, (int) round($width * $scale), (int) round($height * $scale), IMG_BICUBIC);
        imagedestroy($image);

        return $resized;
    }

    private function orient(\GdImage $image, string $path): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = (int) (@exif_read_data($path)['Orientation'] ?? 1);
        $rotated = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => null,
        };

        if ($rotated) {
            imagedestroy($image);

            return $rotated;
        }

        return $image;
    }
}
