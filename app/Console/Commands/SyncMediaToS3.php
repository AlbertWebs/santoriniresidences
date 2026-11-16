<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Support\ResponsiveImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncMediaToS3 extends Command
{
    protected $signature = 'media:sync-s3 {--force : Replace objects already in the destination}';

    protected $description = 'Copy website images, videos, and library uploads to the configured S3 media prefix';

    public function handle(): int
    {
        if (config('filesystems.media_disk') !== 's3') {
            $this->error('Set MEDIA_DISK=s3 before copying media.');

            return self::FAILURE;
        }

        $images = collect($this->recursiveFiles(public_path('media')))
            ->filter(fn ($file) => ResponsiveImage::supports('media/'.basename($file))
                && ! str_contains(str_replace('\\', '/', $file), '/_w/'))
            ->map(fn ($file) => 'media/'.basename($file))
            ->merge(Media::query()->where('mime', 'like', 'image/%')->pluck('path'))
            ->unique()
            ->filter(fn ($path) => ResponsiveImage::supports($path) && is_file(ResponsiveImage::absolute($path)));

        // Generate any missing local WebP variants before copying. This also keeps the
        // migration safe to rerun without repeatedly resizing the same images in S3.
        config(['filesystems.media_disk' => 'public']);
        try {
            $images->each(fn (string $path) => ResponsiveImage::generate($path));
        } finally {
            config(['filesystems.media_disk' => 's3']);
        }

        // Keep the unused render archive out of the bucket. Active website assets
        // live in /media and /icons; uploads live in storage/app/public/cms.
        $files = collect(['media', 'icons'])
            ->flatMap(fn ($directory) => $this->recursiveFiles(public_path($directory)))
            ->merge([public_path('favicon.ico')])
            ->merge(Storage::disk('public')->allFiles(Media::UPLOAD_DIRECTORY))
            ->unique()
            ->filter(fn ($file) => is_file($file) || (! str_starts_with($file, public_path()) && Storage::disk('public')->exists($file)))
            ->values();

        if ($files->isEmpty()) {
            $this->warn('No media files were found.');

            return self::SUCCESS;
        }

        $disk = Storage::disk('s3');
        $existing = $this->option('force') ? collect() : collect($disk->allFiles())->flip();
        $copied = 0;
        $skipped = 0;
        $this->withProgressBar($files, function (string $file) use ($disk, $existing, &$copied, &$skipped) {
            $isPublicFile = str_starts_with($file, public_path());
            $relative = $isPublicFile
                ? str_replace('\\', '/', substr($file, strlen(public_path()) + 1))
                : $file;
            $extension = strtolower(pathinfo($relative, PATHINFO_EXTENSION));
            $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($isPublicFile ? $file : Storage::disk('public')->path($file)) ?: '';

            if (! str_starts_with($mime, 'image/') && ! str_starts_with($mime, 'video/')) {
                $skipped++;

                return;
            }

            $responsivePath = $isPublicFile ? $relative : 'storage/'.$relative;
            if (str_starts_with($mime, 'image/') && ResponsiveImage::supports($responsivePath)
                && (str_starts_with($relative, 'media/') || Media::query()->where('path', $responsivePath)->exists())) {
                ResponsiveImage::generate($responsivePath);
            }

            if ($existing->has($relative)) {
                $skipped++;

                return;
            }

            $stream = $isPublicFile
                ? fopen($file, 'rb')
                : Storage::disk('public')->readStream($file);
            try {
                $disk->put($relative, $stream, [
                    'ContentType' => $mime,
                    'CacheControl' => 'public, max-age=2592000, stale-while-revalidate=86400',
                ]);
            } finally {
                is_resource($stream) && fclose($stream);
            }
            $copied++;
        });

        $this->newLine();
        $this->info("Copied {$copied} media files to the configured S3 prefix; skipped {$skipped} existing or unsupported files.");

        return self::SUCCESS;
    }

    /** @return list<string> */
    private function recursiveFiles(string $directory): array
    {
        if (! is_dir($directory)) {
            return [];
        }

        $files = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)) as $item) {
            if ($item->isFile()) {
                $files[] = $item->getPathname();
            }
        }

        return $files;
    }
}
