<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class DocumentVaultTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    private function lead(string $name = 'Amani Kariuki', string $status = 'won'): Lead
    {
        return Lead::create(['form_type' => 'purchase', 'name' => $name, 'email' => str($name)->slug().'@example.com', 'status' => $status]);
    }

    private function pdf(string $name = 'Offer_letter-1204.pdf'): UploadedFile
    {
        return UploadedFile::fake()->create($name, 120, 'application/pdf');
    }

    public function test_documents_are_uploaded_privately_and_linked_to_customers(): void
    {
        $this->actingAs(User::factory()->create());
        [$amani, $priya] = [$this->lead(), $this->lead('Priya Shah', 'visit_scheduled')];

        $this->from('/admin/legal/documents')->post('/admin/documents', [
            'file' => $this->pdf(),
            'title' => '',
            'category' => 'offer-letter',
            'lead_ids' => [$amani->id, $priya->id],
        ])->assertRedirect('/admin/legal/documents')->assertSessionHasNoErrors();

        $document = Document::firstOrFail();
        $this->assertSame('Offer letter 1204', $document->title);
        $this->assertStringStartsWith('documents/', $document->path);
        Storage::disk('local')->assertExists($document->path);
        $this->assertEqualsCanonicalizing([$amani->id, $priya->id], $document->leads->modelKeys());

        $this->get('/admin/legal/documents')->assertOk()->assertSee('Offer letter 1204')->assertSee('Amani Kariuki');
        $this->get('/admin/legal/documents?q=Priya')->assertOk()->assertSee('Offer letter 1204');
        $this->get('/admin/legal/documents?linked=unlinked')->assertOk()->assertDontSee('Offer letter 1204');
        $this->get("/admin/documents/{$document->id}")->assertOk()->assertSee('Shared with 2 customers');
        $this->get("/admin/leads/{$amani->id}")->assertOk()->assertSee('Offer letter 1204')->assertSee('Copy private link', false);
        $this->get("/admin/documents/{$document->id}/download")->assertOk()->assertHeader('content-disposition');
    }

    public function test_unsafe_files_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post('/admin/documents', [
            'file' => UploadedFile::fake()->createWithContent('agreement.html', '<script>alert(1)</script>'),
            'category' => 'sale-agreement',
        ])->assertSessionHasErrors('file');

        $this->assertSame(0, Document::count());
    }

    public function test_a_document_can_be_edited_replaced_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        [$amani, $priya] = [$this->lead(), $this->lead('Priya Shah')];

        $this->post('/admin/documents', ['file' => $this->pdf(), 'category' => 'offer-letter', 'lead_ids' => [$amani->id]]);
        $document = Document::firstOrFail();
        $original = $document->path;

        $this->put("/admin/documents/{$document->id}", [
            'file' => $this->pdf('Offer letter v2.pdf'),
            'title' => 'Offer letter, signed',
            'category' => 'sale-agreement',
            'lead_ids' => [$priya->id],
        ])->assertRedirect("/admin/documents/{$document->id}")->assertSessionHasNoErrors();

        $document->refresh();
        $this->assertSame('Offer letter, signed', $document->title);
        $this->assertSame('Offer letter v2.pdf', $document->original_name);
        $this->assertSame([$priya->id], $document->leads->modelKeys());
        Storage::disk('local')->assertMissing($original);
        Storage::disk('local')->assertExists($document->path);

        $this->delete("/admin/documents/{$document->id}")->assertRedirect('/admin/legal/documents');
        Storage::disk('local')->assertMissing($document->path);
        $this->assertDatabaseCount('document_lead', 0);
    }

    public function test_documents_can_be_linked_and_unlinked_from_a_lead(): void
    {
        $this->actingAs(User::factory()->create());
        $lead = $this->lead();
        $this->post('/admin/documents', ['file' => $this->pdf(), 'category' => 'floor-plan']);
        $document = Document::firstOrFail();

        $this->from("/admin/leads/{$lead->id}")->post("/admin/leads/{$lead->id}/documents", ['document_id' => $document->id])
            ->assertRedirect("/admin/leads/{$lead->id}");
        $this->assertTrue($lead->documents()->whereKey($document->id)->exists());

        $this->delete("/admin/leads/{$lead->id}/documents/{$document->id}");
        $this->assertFalse($lead->documents()->whereKey($document->id)->exists());
        $this->assertModelExists($document);
    }

    public function test_customers_open_documents_through_signed_links_only(): void
    {
        $this->actingAs(User::factory()->create());
        [$amani, $stranger] = [$this->lead(), $this->lead('Someone Else')];
        $this->post('/admin/documents', ['file' => $this->pdf(), 'category' => 'offer-letter', 'lead_ids' => [$amani->id]]);
        $document = Document::firstOrFail();
        auth()->logout();

        $this->get("/admin/documents/{$document->id}/download")->assertRedirect('/admin/login');
        $this->get("/documents/{$document->id}/shared/{$amani->id}")->assertForbidden();

        $this->get($document->shareUrl($amani))->assertOk();
        $this->get($document->shareUrl($amani))->assertOk();
        $pivot = $document->leads()->whereKey($amani->id)->first()->pivot;
        $this->assertSame(2, (int) $pivot->downloads);
        $this->assertNotNull($pivot->last_downloaded_at);

        $this->get($document->shareUrl($stranger))->assertNotFound();

        $expired = URL::temporarySignedRoute('documents.shared', now()->subMinute(), ['document' => $document->id, 'lead' => $amani->id]);
        $this->get($expired)->assertForbidden();
    }

    public function test_the_customer_search_returns_matching_leads(): void
    {
        $this->actingAs(User::factory()->create());
        $this->lead();
        $this->lead('Priya Shah');

        $this->getJson('/admin/leads/search?q=priya')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Priya Shah')
            ->assertJsonPath('data.0.status_label', 'Won');
    }
}
