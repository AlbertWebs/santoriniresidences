<?php

namespace Tests\Feature;

use App\Support\ResponsiveImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResponsiveImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_widths_step_below_the_original_and_include_a_full_width_copy(): void
    {
        $this->assertSame([400, 800, 900], ResponsiveImage::widthsFor(900));
        $this->assertSame([400, 800, 1200, 1600, 2400], ResponsiveImage::widthsFor(3200));
        $this->assertSame([400, 800, 1200, 1600, 2400], ResponsiveImage::widthsFor(2400));
    }

    public function test_variants_are_generated_offered_as_a_srcset_and_deleted(): void
    {
        $path = 'storage/cms/responsive-test-'.uniqid().'.png';
        $file = ResponsiveImage::absolute($path);
        @mkdir(dirname($file), 0755, true);
        $image = imagecreatetruecolor(900, 600);
        imagepng($image, $file);
        imagedestroy($image);

        try {
            $this->assertSame(3, ResponsiveImage::generate($path));
            $this->assertSame(0, ResponsiveImage::generate($path));

            $srcset = ResponsiveImage::srcset($path);
            $this->assertStringContainsString('-png-400.webp 400w', $srcset);
            $this->assertStringContainsString('-png-900.webp 900w', $srcset);
            $this->assertStringEndsWith('-png-900.webp', ResponsiveImage::url($path, 1600));

            ResponsiveImage::delete($path);
            $this->assertSame('', ResponsiveImage::srcset($path));
            $this->assertFileDoesNotExist(ResponsiveImage::absolute(ResponsiveImage::variantPath($path, 400)));
        } finally {
            ResponsiveImage::delete($path);
            @unlink($file);
        }
    }

    public function test_uploaded_images_and_responsive_variants_can_live_on_s3(): void
    {
        Storage::fake('s3');
        config(['filesystems.media_disk' => 's3']);

        $source = tempnam(sys_get_temp_dir(), 'santorini-source-');
        $image = imagecreatetruecolor(900, 600);
        imagepng($image, $source);
        imagedestroy($image);

        try {
            $media = app(\App\Support\MediaProcessor::class)->store($source, 'Cloud library image.png');
            $object = substr($media->path, strlen('storage/'));

            Storage::disk('s3')->assertExists($object);
            Storage::disk('s3')->assertExists(substr(ResponsiveImage::variantPath($media->path, 400), strlen('storage/')));
            $this->assertStringContainsString('/'.$object, $media->url);
            $this->assertStringContainsString('-800.webp 800w', ResponsiveImage::srcset($media->path));

            Storage::disk('s3')->delete($object);
            ResponsiveImage::delete($media->path);
        } finally {
            @unlink($source);
        }
    }

    public function test_cloud_media_urls_use_the_santorini_prefix_for_uploaded_files(): void
    {
        config([
            'filesystems.media_disk' => 's3',
            'filesystems.disks.s3.key' => 'test-key',
            'filesystems.disks.s3.secret' => 'test-secret',
            'filesystems.disks.s3.region' => 'eu-north-1',
            'filesystems.disks.s3.bucket' => 'santorini-media-test',
            'filesystems.disks.s3.root' => 'santorini-residences',
            'filesystems.disks.s3.url' => null,
        ]);
        Storage::forgetDisk('s3');

        $this->assertSame(
            'https://santorini-media-test.s3.eu-north-1.amazonaws.com/santorini-residences/cms/example.webp',
            cms_asset('storage/cms/example.webp'),
        );
    }

    public function test_same_named_images_in_different_formats_do_not_share_variants(): void
    {
        $this->assertNotSame(
            ResponsiveImage::variantPath('media/logo.png', 400),
            ResponsiveImage::variantPath('media/logo.jpg', 400),
        );
        $this->assertSame('media/_w/kitchen-800.webp', ResponsiveImage::variantPath('media/kitchen.webp', 800));
    }

    public function test_pages_link_the_favicon_and_defer_the_hero_film(): void
    {
        $response = $this->get('/')->assertOk();

        $response->assertSee('icons/apple-touch-icon.png', false);
        $response->assertSee('rel="manifest"', false);
        $response->assertSee('data-src="'.asset('media/hero-film-1080.mp4').'"', false);
        $response->assertSee('preload="none"', false);
        $response->assertDontSee('<source src=', false);
    }

    public function test_images_are_served_with_a_srcset_when_variants_exist(): void
    {
        if (! ResponsiveImage::variants('media/hero-night.webp')) {
            $this->markTestSkipped('Run php artisan media:variants to create the image variants.');
        }

        $this->get('/')->assertOk()->assertSee('media/_w/hero-night-800.webp 800w', false);
    }
}
