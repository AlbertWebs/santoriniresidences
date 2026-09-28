<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\Funnel;
use App\Models\Lead;
use App\Models\LeadForm;
use App\Models\Media;
use App\Models\User;
use App\Support\ContentSchema;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_admin_requires_login(): void
    {
        foreach (['/admin', '/admin/website', '/admin/media', '/admin/leads', '/admin/forms', '/admin/funnels'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }

        $this->postJson('/admin/media/chunk')->assertUnauthorized();
        $this->get('/admin/login')->assertOk();
    }

    public function test_an_admin_can_sign_in_and_out(): void
    {
        $user = User::factory()->create(['email' => 'editor@example.com', 'password' => 'correct-horse-battery']);

        $this->post('/admin/login', ['email' => 'editor@example.com', 'password' => 'wrong-password'])
            ->assertSessionHasErrors();
        $this->assertGuest();

        $this->post('/admin/login', ['email' => 'editor@example.com', 'password' => 'correct-horse-battery'])
            ->assertRedirect();
        $this->assertAuthenticatedAs($user);

        $this->post('/admin/logout')->assertRedirect();
        $this->assertGuest();
    }

    public function test_every_admin_screen_renders(): void
    {
        $lead = Lead::create([
            'form_type' => 'book-visit', 'name' => 'Visit Lead', 'email' => 'visit@example.com',
            'preferred_date' => now()->addDays(2)->toDateString(), 'visit_type' => 'in-person', 'status' => 'new',
        ]);
        $funnel = Funnel::firstOrFail();

        $this->actingAs(User::factory()->create());

        $urls = [
            '/admin/website', '/admin/media', '/admin/media/library',
            '/admin/leads', '/admin/leads/visits', "/admin/leads/{$lead->id}",
            '/admin/forms', '/admin/forms/book-visit/edit', '/admin/forms/general/edit',
            '/admin/funnels', '/admin/funnels/create', "/admin/funnels/{$funnel->id}/edit",
            '/admin/dashboard',
        ];

        foreach (array_keys(ContentSchema::pages()) as $page) {
            $urls[] = "/admin/website/{$page}";
        }

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }

        $this->get('/admin/leads/export')->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_dashboard_reports_every_range_and_demo_data_can_be_removed(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/dashboard')->assertOk()->assertSee('No leads yet.', false);

        (new DemoDataSeeder)->run(40);
        $this->assertSame(40, Lead::where('meta->demo', true)->count());

        foreach (array_keys(DashboardController::RANGES) as $range) {
            $this->get("/admin/dashboard?range={$range}")->assertOk()->assertSee('Enquiries by source')->assertSee('Campaign performance');
        }

        $this->get('/admin/dashboard?range=bogus')->assertOk()->assertSee('The last 30 days');

        $real = Lead::create(['form_type' => 'general', 'name' => 'Real Buyer', 'email' => 'real@example.com', 'status' => 'new']);
        $this->assertSame(40, DemoDataSeeder::purge());
        $this->assertModelExists($real);
        $this->assertSame(0, Funnel::where('slug', 'like', 'demo-%')->count());
    }

    public function test_leads_can_be_filtered_updated_and_exported_safely(): void
    {
        $lead = Lead::create(['form_type' => 'general', 'name' => '=HYPERLINK("x")', 'email' => 'formula@example.com', 'status' => 'new']);
        Lead::create(['form_type' => 'purchase', 'name' => 'Other', 'email' => 'other@example.com', 'status' => 'won']);

        $this->actingAs(User::factory()->create());

        $this->get('/admin/leads?status=new')->assertOk()->assertSee('formula@example.com')->assertDontSee('other@example.com');

        $this->patch("/admin/leads/{$lead->id}", ['status' => 'contacted', 'notes' => 'Called on Monday.'])->assertRedirect();
        $this->assertSame('contacted', $lead->fresh()->status);

        $csv = $this->get('/admin/leads/export')->streamedContent();
        $this->assertStringContainsString("'=HYPERLINK", $csv);
    }

    public function test_form_fields_can_be_toggled_but_system_forms_stay(): void
    {
        $this->actingAs(User::factory()->create());
        $form = LeadForm::where('key', 'price-list')->firstOrFail();

        $this->put('/admin/forms/price-list', [
            'title' => 'Receive the price list',
            'intro' => 'Shared privately.',
            'button_label' => 'Send it to me',
            'success_title' => 'Thank you.',
            'success_message' => 'On its way.',
            'is_active' => '1',
            'fields' => [
                'phone' => ['enabled' => '1', 'required' => '1', 'label' => 'Mobile'],
                'residence_type' => ['enabled' => '1'],
            ],
        ])->assertRedirect()->assertSessionHasNoErrors();

        $active = $form->fresh()->activeFields();
        $this->assertSame('Receive the price list', $form->fresh()->title);
        $this->assertTrue($active['phone']['required']);
        $this->assertSame('Mobile', $active['phone']['label']);
        $this->assertArrayHasKey('residence_type', $active);
        $this->assertArrayNotHasKey('message', $active);

        $this->delete('/admin/forms/book-visit')->assertSessionHas('error');
        $this->assertDatabaseHas('lead_forms', ['key' => 'book-visit']);
    }

    public function test_funnels_can_be_created_with_a_unique_slug(): void
    {
        $this->actingAs(User::factory()->create());
        $form = LeadForm::where('key', 'purchase')->firstOrFail();

        $payload = [
            'name' => 'TikTok purchase',
            'channel' => 'tiktok',
            'lead_form_id' => $form->id,
            'headline' => 'Own a residence',
            'is_active' => '1',
        ];

        $this->post('/admin/funnels', $payload)->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('funnels', ['slug' => 'tiktok-purchase', 'channel' => 'tiktok']);

        $this->post('/admin/funnels', $payload)->assertSessionHasErrors('slug');

        $this->get('/go/tiktok-purchase')->assertOk()->assertSee('Own a residence');
    }

    public function test_chunked_uploads_are_assembled_and_converted_to_webp(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $image = imagecreatetruecolor(3000, 1500);
        imagefill($image, 0, 0, imagecolorallocate($image, 24, 40, 72));
        ob_start();
        imagejpeg($image, null, 90);
        $bytes = ob_get_clean();

        $parts = str_split($bytes, (int) ceil(strlen($bytes) / 3));
        $response = null;

        foreach ($parts as $index => $part) {
            $response = $this->post('/admin/media/chunk', [
                'upload_id' => 'test-upload-0001',
                'index' => $index,
                'total' => count($parts),
                'name' => 'Sky pool at dusk.jpg',
                'size' => strlen($bytes),
                'chunk' => UploadedFile::fake()->createWithContent('blob', $part),
            ], ['Accept' => 'application/json'])->assertOk();

            $this->assertSame($index === count($parts) - 1, $response->json('done'));
        }

        $media = Media::findOrFail($response->json('media.id'));
        $this->assertSame('image/webp', $media->mime);
        $this->assertSame(2560, $media->width);
        $this->assertSame(1280, $media->height);
        $this->assertSame('Sky pool at dusk', $media->alt);
        $this->assertStringStartsWith('storage/cms/', $media->path);
        Storage::disk('public')->assertExists(substr($media->path, strlen('storage/')));

        $this->deleteJson("/admin/media/{$media->id}")->assertOk();
        Storage::disk('public')->assertMissing(substr($media->path, strlen('storage/')));
    }

    public function test_uploads_reject_unsupported_files(): void
    {
        $this->actingAs(User::factory()->create());

        $this->postJson('/admin/media/chunk', [
            'upload_id' => 'test-upload-0002',
            'index' => 0,
            'total' => 1,
            'name' => 'notes.pdf',
            'size' => 10,
            'chunk' => UploadedFile::fake()->createWithContent('blob', '0123456789'),
        ])->assertUnprocessable()->assertJsonValidationErrors('chunk');
    }
}
