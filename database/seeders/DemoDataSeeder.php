<?php

namespace Database\Seeders;

use App\Models\Funnel;
use App\Models\Lead;
use App\Models\LeadForm;
use App\Support\LeadFormTypes;
use App\Support\SiteContent;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

/**
 * Sample leads and funnels for previewing the dashboard. Never called from DatabaseSeeder;
 * run with `php artisan cms:demo` and remove with `php artisan cms:demo --purge`.
 */
class DemoDataSeeder extends Seeder
{
    public const SLUG_PREFIX = 'demo-';

    private const FIRST = ['Amani', 'Wanjiru', 'Kamau', 'Achieng', 'Otieno', 'Njeri', 'Mwangi', 'Zawadi', 'Baraka', 'Imani', 'Priya', 'Arjun', 'Fatuma', 'Hassan', 'Sophie', 'James', 'Leila', 'Daniel', 'Grace', 'Omar', 'Chloe', 'Kofi', 'Nadia', 'Liam', 'Aisha', 'Ethan', 'Mercy', 'Samuel', 'Elena', 'Yusuf'];

    private const LAST = ['Kariuki', 'Odhiambo', 'Mutua', 'Wambui', 'Chege', 'Onyango', 'Kimani', 'Patel', 'Shah', 'Abdi', 'Mohamed', 'Njoroge', 'Maina', 'Hughes', 'Laurent', 'Mensah', 'Rossi', 'Kiplagat', 'Barasa', 'Wekesa', 'Haddad', 'Okafor', 'Müller', 'Nyambura'];

    private const FUNNELS = [
        ['name' => 'Instagram: private viewing', 'channel' => 'instagram', 'form' => 'book-visit', 'weight' => 34],
        ['name' => 'Facebook: price list', 'channel' => 'facebook', 'form' => 'price-list', 'weight' => 24],
        ['name' => 'TikTok: sky pool film', 'channel' => 'tiktok', 'form' => 'general', 'weight' => 16],
        ['name' => 'LinkedIn: investor pack', 'channel' => 'linkedin', 'form' => 'investment-pack', 'weight' => 14],
        ['name' => 'WhatsApp: broadcast list', 'channel' => 'whatsapp', 'form' => 'schedule-visit', 'weight' => 12],
    ];

    public function run(int $count = 600): void
    {
        $tz = config('site.timezone', config('app.timezone'));
        $now = CarbonImmutable::now($tz);
        $forms = LeadForm::pluck('id', 'key');

        $funnels = collect(self::FUNNELS)->map(function (array $spec) use ($forms) {
            $funnel = Funnel::firstOrCreate(['slug' => self::SLUG_PREFIX.str($spec['name'])->slug()], [
                'name' => $spec['name'],
                'channel' => $spec['channel'],
                'lead_form_id' => $forms[$spec['form']],
                'headline' => 'Santorini Residences',
                'headline_accent' => 'Westlands.',
                'utm_source' => $spec['channel'],
                'utm_medium' => 'social',
                'utm_campaign' => str($spec['name'])->after(': ')->slug()->toString(),
                'is_active' => $spec['channel'] !== 'whatsapp',
            ]);

            return ['model' => $funnel, 'form' => $spec['form'], 'weight' => $spec['weight']];
        });

        $rows = [];
        $perFunnel = [];

        for ($i = 0; $i < $count; $i++) {
            $daysAgo = (int) floor(365 * (1 - sqrt(mt_rand() / mt_getrandmax())));
            $createdAt = $now->subDays($daysAgo)->setTime($this->pickHour(), mt_rand(0, 59), mt_rand(0, 59));
            if ($createdAt->gt($now)) {
                $createdAt = $now->subMinutes(mt_rand(5, 600));
            }

            $funnel = mt_rand(1, 100) <= 42 ? $this->weighted($funnels->all()) : null;
            $type = $funnel['form'] ?? $this->weighted([
                ['v' => 'general', 'weight' => 34], ['v' => 'book-visit', 'weight' => 24], ['v' => 'price-list', 'weight' => 16],
                ['v' => 'schedule-visit', 'weight' => 10], ['v' => 'investment-pack', 'weight' => 8], ['v' => 'purchase', 'weight' => 8],
            ])['v'];

            $first = self::FIRST[array_rand(self::FIRST)];
            $last = self::LAST[array_rand(self::LAST)];
            $isVisit = in_array($type, ['book-visit', 'schedule-visit'], true);
            $website = $funnel ? null : $this->weighted([['v' => null, 'weight' => 70], ['v' => 'google', 'weight' => 20], ['v' => 'newsletter', 'weight' => 10]])['v'];

            $rows[] = [
                'form_type' => $type,
                'lead_form_id' => $forms[$type] ?? null,
                'funnel_id' => $funnel['model']->id ?? null,
                'name' => "$first $last",
                'email' => strtolower(str("$first.$last")->ascii()).mt_rand(1, 99).'@example.com',
                'phone' => '+2547'.mt_rand(10000000, 99999999),
                'interest' => $type === 'general' ? array_rand(SiteContent::interests()) : null,
                'preferred_date' => $isVisit ? $createdAt->addDays(mt_rand(2, 16))->toDateString() : null,
                'preferred_time' => $isVisit ? LeadFormTypes::TIMES[array_rand(LeadFormTypes::TIMES)] : null,
                'visit_type' => match ($type) {
                    'book-visit' => 'in-person', 'schedule-visit' => 'virtual', default => null
                },
                'residence_type' => $type === 'general' ? null : $this->weighted([
                    ['v' => 'two-bedroom', 'weight' => 34], ['v' => 'one-bedroom', 'weight' => 26], ['v' => 'three-bedroom', 'weight' => 20],
                    ['v' => 'loft', 'weight' => 12], ['v' => 'undecided', 'weight' => 8],
                ])['v'],
                'guests' => $type === 'book-visit' ? mt_rand(1, 4) : null,
                'contact_channel' => $type === 'schedule-visit' ? array_rand(LeadFormTypes::CHANNELS) : null,
                'source' => $funnel ? 'funnel' : 'website',
                'utm_source' => $funnel ? $funnel['model']->utm_source : $website,
                'utm_medium' => $funnel ? 'social' : ($website ? ($website === 'google' ? 'cpc' : 'email') : null),
                'utm_campaign' => $funnel['model']->utm_campaign ?? null,
                'status' => $this->statusFor($daysAgo),
                'meta' => json_encode(['demo' => true]),
                'created_at' => $createdAt->utc()->toDateTimeString(),
                'updated_at' => $createdAt->utc()->toDateTimeString(),
            ];

            if ($funnel) {
                $perFunnel[$funnel['model']->id] = ($perFunnel[$funnel['model']->id] ?? 0) + 1;
            }
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            Lead::insert($chunk);
        }

        foreach ($funnels as $funnel) {
            $leads = $perFunnel[$funnel['model']->id] ?? 0;
            $funnel['model']->increment('visits', $leads * mt_rand(9, 26) + mt_rand(20, 80));
        }
    }

    public static function purge(): int
    {
        $deleted = Lead::where('meta->demo', true)->delete();
        Funnel::where('slug', 'like', self::SLUG_PREFIX.'%')->delete();

        return $deleted;
    }

    private function pickHour(): int
    {
        return (int) $this->weighted([
            ['v' => 7, 'weight' => 3], ['v' => 8, 'weight' => 5], ['v' => 9, 'weight' => 8], ['v' => 10, 'weight' => 9],
            ['v' => 11, 'weight' => 8], ['v' => 12, 'weight' => 9], ['v' => 13, 'weight' => 10], ['v' => 14, 'weight' => 7],
            ['v' => 15, 'weight' => 6], ['v' => 16, 'weight' => 6], ['v' => 17, 'weight' => 6], ['v' => 18, 'weight' => 7],
            ['v' => 19, 'weight' => 10], ['v' => 20, 'weight' => 12], ['v' => 21, 'weight' => 10], ['v' => 22, 'weight' => 6],
            ['v' => 23, 'weight' => 3], ['v' => 1, 'weight' => 1], ['v' => 5, 'weight' => 1],
        ])['v'];
    }

    private function statusFor(int $daysAgo): string
    {
        $weights = match (true) {
            $daysAgo < 3 => ['new' => 80, 'contacted' => 18, 'visit_scheduled' => 2],
            $daysAgo < 14 => ['new' => 20, 'contacted' => 45, 'visit_scheduled' => 30, 'lost' => 5],
            $daysAgo < 60 => ['contacted' => 30, 'visit_scheduled' => 25, 'won' => 20, 'lost' => 25],
            default => ['contacted' => 10, 'visit_scheduled' => 5, 'won' => 35, 'lost' => 50],
        };

        return $this->weighted(array_map(fn ($status, $weight) => ['v' => $status, 'weight' => $weight], array_keys($weights), $weights))['v'];
    }

    private function weighted(array $options): array
    {
        $roll = mt_rand(1, array_sum(array_column($options, 'weight')));
        foreach ($options as $option) {
            if (($roll -= $option['weight']) <= 0) {
                return $option;
            }
        }

        return end($options);
    }
}
