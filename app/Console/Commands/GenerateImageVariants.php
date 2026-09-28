<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Support\ResponsiveImage;
use Illuminate\Console\Command;

class GenerateImageVariants extends Command
{
    protected $signature = 'media:variants {--force : Rebuild variants that already exist}';

    protected $description = 'Create the resized WebP copies used for responsive images';

    public function handle(): int
    {
        $paths = collect(glob(public_path('media/*.{webp,jpg,jpeg,png}'), GLOB_BRACE) ?: [])
            ->map(fn ($file) => 'media/'.basename($file))
            ->merge(Media::query()->where('mime', 'like', 'image/%')->pluck('path'))
            ->unique()
            ->filter(fn ($path) => ResponsiveImage::supports($path) && is_file(ResponsiveImage::absolute($path)))
            ->values();

        $created = 0;
        $this->withProgressBar($paths, function (string $path) use (&$created) {
            $created += ResponsiveImage::generate($path, (bool) $this->option('force'));
        });

        $this->newLine();
        $this->info("Created {$created} variants for {$paths->count()} images.");

        return self::SUCCESS;
    }
}
