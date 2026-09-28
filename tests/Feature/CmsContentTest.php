<?php

namespace Tests\Feature;

use App\Models\ContentBlock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_with_schema_defaults(): void
    {
        $this->get('/')->assertOk()->assertSee('A new landmark of urban resort living.');

        foreach (['/residences', '/gallery', '/about', '/enquire', '/book-a-visit'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_saved_content_replaces_the_default_and_empty_images_fall_back(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->put('/admin/website/home', ['content' => ['hero' => [
                'tagline' => 'A quieter headline for testing.',
                'image' => '',
            ]]])
            ->assertRedirect('/admin/website/home')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('content_blocks', ['key' => 'home.hero']);
        $this->assertSame('A quieter headline for testing.', cms('home.hero.tagline'));
        $this->assertSame('media/hero-night.webp', cms('home.hero.image'));

        $this->get('/')->assertOk()->assertSee('A quieter headline for testing.');
    }

    public function test_a_section_can_be_restored_to_the_original(): void
    {
        $admin = User::factory()->create();
        ContentBlock::create(['key' => 'home.hero', 'data' => ['tagline' => 'Temporary']]);
        cms()->flush();

        $this->actingAs($admin)->put('/admin/website/home', ['reset' => 'hero'])->assertRedirect();

        $this->assertDatabaseMissing('content_blocks', ['key' => 'home.hero']);
        $this->assertSame('A new landmark of urban resort living.', cms('home.hero.tagline'));
    }

    public function test_unknown_pages_are_not_found(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin/website/nope')->assertNotFound();
    }
}
