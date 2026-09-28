<?php

namespace Database\Seeders;

use App\Models\Funnel;
use App\Models\LeadForm;
use App\Models\User;
use App\Support\LeadFormTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@santoriniresidences.com');
        $password = env('ADMIN_PASSWORD');

        $user = User::firstOrNew(['email' => $email]);
        if (! $user->exists) {
            $user->forceFill([
                'name' => 'Santorini Admin',
                'password' => $password ?: Str::password(20),
            ])->save();

            if (! $password) {
                $this->command?->warn('ADMIN_PASSWORD is not set. Set it in .env and run: php artisan cms:admin');
            }
        }

        foreach (LeadFormTypes::types() as $type => $preset) {
            LeadForm::firstOrCreate(['key' => $type], [
                'type' => $type,
                'title' => $preset['default_title'],
                'intro' => $preset['default_intro'],
                'button_label' => $preset['default_button'],
                'success_title' => 'Thank you.',
                'success_message' => 'Your request has been received. A member of the Santorini team will be in touch personally.',
                'fields' => LeadFormTypes::defaultSettings($type),
                'is_active' => true,
            ]);
        }

        $bookVisit = LeadForm::where('key', 'book-visit')->first();
        Funnel::firstOrCreate(['slug' => 'instagram-book-a-visit'], [
            'name' => 'Instagram: book a site visit',
            'channel' => 'instagram',
            'lead_form_id' => $bookVisit->id,
            'headline' => 'Visit Santorini',
            'headline_accent' => 'in person.',
            'subline' => 'Choose a preferred date and time to see the landmark on Lantana Road, Westlands.',
            'utm_source' => 'instagram',
            'utm_medium' => 'social',
            'utm_campaign' => 'book-a-visit',
            'is_active' => true,
        ]);

        $this->call(MediaLibrarySeeder::class);
    }
}
