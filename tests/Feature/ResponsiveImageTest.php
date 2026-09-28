<?php

namespace Tests\Feature;

use App\Support\ResponsiveImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
