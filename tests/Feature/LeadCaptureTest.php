<?php

namespace Tests\Feature;

use App\Models\Funnel;
use App\Models\Lead;
use App\Models\LeadForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadCaptureTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_the_general_enquiry_is_stored(): void
    {
        $this->from('/enquire')->post('/enquire', [
            'name' => 'Amina Otieno',
            'email' => 'amina@example.com',
            'interest' => 'price-list',
            'message' => 'Please share the price list.',
        ])->assertRedirect()->assertSessionHas('lead_submitted', 'general');

        $this->assertDatabaseHas('leads', [
            'email' => 'amina@example.com',
            'form_type' => 'general',
            'interest' => 'price-list',
            'source' => 'website',
            'status' => 'new',
        ]);
    }

    public function test_a_site_visit_requires_a_date_and_records_an_in_person_visit(): void
    {
        $form = LeadForm::where('key', 'book-visit')->firstOrFail();

        $this->from('/book-a-visit')->post("/forms/{$form->key}", [
            'name' => 'Brian Mwangi',
            'email' => 'brian@example.com',
            'phone' => '+254700000000',
        ])->assertSessionHasErrors('preferred_date');

        $this->from('/book-a-visit')->post("/forms/{$form->key}", [
            'name' => 'Brian Mwangi',
            'email' => 'brian@example.com',
            'phone' => '+254700000000',
            'preferred_date' => now()->addDays(3)->toDateString(),
            'preferred_time' => 'Morning',
            'guests' => 2,
            'residence_type' => 'loft',
        ])->assertSessionHasNoErrors()->assertSessionHas('lead_submitted', 'book-visit');

        $lead = Lead::where('email', 'brian@example.com')->firstOrFail();
        $this->assertSame('in-person', $lead->visit_type);
        $this->assertSame('loft', $lead->residence_type);
        $this->assertSame(2, (int) $lead->guests);
    }

    public function test_every_preset_form_accepts_a_valid_submission(): void
    {
        $payloads = [
            'schedule-visit' => ['phone' => '+254711111111', 'contact_channel' => 'video'],
            'purchase' => ['phone' => '+254722222222', 'residence_type' => 'two-bedroom'],
            'price-list' => [],
            'investment-pack' => [],
        ];

        foreach ($payloads as $key => $extra) {
            $form = LeadForm::where('key', $key)->firstOrFail();

            $this->get("/forms/{$form->key}")->assertOk()->assertSee($form->title);

            $this->from("/forms/{$form->key}")->post("/forms/{$form->key}", array_merge([
                'name' => 'Test '.$key,
                'email' => $key.'@example.com',
            ], $extra))->assertSessionHasNoErrors();

            $this->assertDatabaseHas('leads', ['email' => $key.'@example.com', 'form_type' => $key]);
        }

        $this->assertSame('virtual', Lead::where('form_type', 'schedule-visit')->value('visit_type'));
    }

    public function test_disabled_fields_are_ignored_and_inactive_forms_are_closed(): void
    {
        $form = LeadForm::where('key', 'purchase')->firstOrFail();
        $fields = $form->fields;
        $fields['residence_type'] = ['enabled' => false, 'required' => true];
        $form->update(['fields' => $fields]);

        $this->post("/forms/{$form->key}", [
            'name' => 'No residence',
            'email' => 'noresidence@example.com',
            'phone' => '+254733333333',
            'residence_type' => 'loft',
        ])->assertSessionHasNoErrors();

        $this->assertNull(Lead::where('email', 'noresidence@example.com')->value('residence_type'));

        $form->update(['is_active' => false]);
        $this->get("/forms/{$form->key}")->assertNotFound();
        $this->post("/forms/{$form->key}", ['name' => 'x', 'email' => 'x@example.com'])->assertNotFound();
    }

    public function test_the_honeypot_silently_discards_bots(): void
    {
        $this->post('/enquire', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'interest' => 'price-list',
            'company' => 'Spam Ltd',
        ])->assertRedirect()->assertSessionHas('lead_submitted', 'general');

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_a_funnel_counts_visits_and_attributes_the_lead(): void
    {
        $funnel = Funnel::where('slug', 'instagram-book-a-visit')->firstOrFail();

        $this->get('/go/instagram-book-a-visit?utm_campaign=spring-launch')
            ->assertOk()
            ->assertSee('Visit Santorini');

        $this->assertSame(1, (int) $funnel->fresh()->visits);

        $this->post('/forms/book-visit', [
            'name' => 'Funnel Lead',
            'email' => 'funnel@example.com',
            'phone' => '+254744444444',
            'preferred_date' => now()->addWeek()->toDateString(),
        ])->assertSessionHasNoErrors();

        $lead = Lead::where('email', 'funnel@example.com')->firstOrFail();
        $this->assertSame($funnel->id, $lead->funnel_id);
        $this->assertSame('funnel', $lead->source);
        $this->assertSame('instagram', $lead->utm_source);
        $this->assertSame('spring-launch', $lead->utm_campaign);
    }

    public function test_inactive_funnels_are_not_found(): void
    {
        Funnel::where('slug', 'instagram-book-a-visit')->update(['is_active' => false]);

        $this->get('/go/instagram-book-a-visit')->assertNotFound();
    }
}
