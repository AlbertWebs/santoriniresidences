<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\TestimonialContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_testimonials_stay_hidden_until_a_complete_entry_is_published(): void
    {
        $this->actingAs(User::factory()->create());
        $entry = [
            'id' => (string) Str::uuid(),
            'type' => 'Testimonial',
            'name' => 'A Santorini resident',
            'headline' => 'A considered experience',
            'content' => 'The team made the whole process feel clear and personal.',
        ];

        $this->get('/admin/cms/testimonials')->assertOk()->assertSee('Show publicly on the website');
        $this->get('/testimonials')->assertNotFound();

        $this->put('/admin/cms/testimonials', ['items' => [$entry]])
            ->assertRedirect(route('admin.cms.testimonials.index'));
        $this->assertSame([], TestimonialContent::published());
        $this->get('/testimonials')->assertNotFound();

        $this->put('/admin/cms/testimonials', ['items' => [$entry + ['is_public' => '1']]])
            ->assertRedirect(route('admin.cms.testimonials.index'));
        $this->get('/testimonials')->assertOk()->assertSee($entry['content']);
        $this->get('/')->assertSee(route('testimonials'));
    }
}
