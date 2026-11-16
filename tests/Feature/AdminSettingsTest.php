<?php

namespace Tests\Feature;

use App\Models\ContentBlock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_admin_settings_load_with_local_defaults_and_persist_changes(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/settings')
            ->assertOk()
            ->assertSee('KES — Kenyan Shilling')
            ->assertSee('Africa/Nairobi');

        $this->put('/admin/settings', [
            'currency' => 'KES',
            'timezone' => 'Africa/Nairobi',
            'notify_new_inquiries' => '1',
            'daily_summary' => '1',
        ])->assertRedirect('/admin/settings')->assertSessionHasNoErrors();

        $this->assertSame([
            'currency' => 'KES',
            'timezone' => 'Africa/Nairobi',
            'notify_new_inquiries' => true,
            'notify_visit_confirmations' => false,
            'daily_summary' => true,
        ], ContentBlock::where('key', 'system.settings')->firstOrFail()->data);

        $this->get('/admin/settings')->assertOk()->assertSee('value="KES" selected', false);
    }

    public function test_settings_reject_unsupported_currency_and_timezone(): void
    {
        $this->actingAs(User::factory()->create());

        $this->put('/admin/settings', [
            'currency' => 'GBP',
            'timezone' => 'Mars/Olympus',
        ])->assertSessionHasErrors(['currency', 'timezone']);
    }
}
