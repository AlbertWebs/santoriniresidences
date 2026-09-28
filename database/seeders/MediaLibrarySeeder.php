<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Registers the original site imagery and the project film in the media library, in place.
 */
class MediaLibrarySeeder extends Seeder
{
    public function run(): void
    {
        $files = array_merge(
            glob(public_path('media/*.webp')) ?: [],
            glob(public_path('media/*.jpg')) ?: [],
            glob(public_path('media/*.png')) ?: [],
            glob(public_path('media/*.mp4')) ?: [],
            [public_path('content/MAIN PROJECT RENDERS VIDEO.mp4')],
        );

        foreach ($files as $file) {
            if (! is_file($file) || str_contains(basename($file), 'logo-santorini')) {
                continue;
            }

            $path = str_replace('\\', '/', substr($file, strlen(public_path()) + 1));
            $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file) ?: 'application/octet-stream';
            [$width, $height] = str_starts_with($mime, 'image/') ? (getimagesize($file) ?: [null, null]) : [null, null];

            Media::firstOrCreate(['path' => $path], [
                'original_name' => basename($file),
                'mime' => $mime,
                'size' => filesize($file),
                'width' => $width,
                'height' => $height,
                'alt' => Str::of(pathinfo($file, PATHINFO_FILENAME))->replace('-', ' ')->lower()->ucfirst()->toString(),
            ]);
        }
    }
}
