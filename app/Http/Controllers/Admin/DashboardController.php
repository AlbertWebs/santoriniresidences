<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\Funnel;
use App\Models\Lead;
use App\Models\LeadForm;
use App\Models\Media;
use App\Support\LeadFormTypes;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public const RANGES = [
        '7d' => ['label' => '7 days', 'days' => 7, 'bucket' => 'day'],
        '30d' => ['label' => '30 days', 'days' => 30, 'bucket' => 'day'],
        '90d' => ['label' => '90 days', 'days' => 91, 'bucket' => 'week'],
        '12m' => ['label' => '12 months', 'days' => null, 'bucket' => 'month'],
    ];

    private const STATUS_COLOURS = [
        'new' => '#d2ad65',
        'contacted' => '#5b7aa6',
        'visit_scheduled' => '#0e1e37',
        'won' => '#2f6b4f',
        'lost' => '#cfc4b2',
    ];

    public function __invoke(Request $request): View
    {
        $range = array_key_exists($request->query('range'), self::RANGES) ? $request->query('range') : '30d';
        $tz = config('site.timezone', config('app.timezone'));
        $now = CarbonImmutable::now($tz);
        [$start, $previousStart] = $this->window($range, $now);

        $leads = Lead::query()
            ->where('created_at', '>=', $previousStart->utc())
            ->get(['id', 'created_at', 'funnel_id', 'status', 'form_type', 'residence_type', 'interest', 'utm_source'])
            ->each(fn (Lead $lead) => $lead->setAttribute('local_at', $lead->created_at->copy()->setTimezone($tz)));

        [$current, $previous] = $leads->partition(fn (Lead $lead) => $lead->local_at->gte($start));
        $buckets = $this->buckets($range, $start, $now);
        $isVisit = fn (Lead $lead) => in_array($lead->form_type, LeadController::VISIT_TYPES, true);
        $isFunnel = fn (Lead $lead) => $lead->funnel_id !== null;

        $funnelLeadsTotal = Lead::whereNotNull('funnel_id')->count();
        $funnelVisitsTotal = (int) Funnel::sum('visits');
        $oldestWaiting = Lead::where('status', 'new')->oldest()->value('created_at');

        return view('admin.dashboard', [
            'range' => $range,
            'ranges' => self::RANGES,
            'daypart' => match (true) {
                $now->hour < 12 => 'morning',
                $now->hour < 17 => 'afternoon',
                default => 'evening',
            },
            'today' => $now,
            'kpis' => [
                $this->kpi('Enquiries', $current, $previous, $buckets, fn () => true, 'All forms and funnels'),
                $this->kpi('Visit requests', $current, $previous, $buckets, $isVisit, 'On site and virtual'),
                $this->kpi('From social funnels', $current, $previous, $buckets, $isFunnel,
                    $funnelVisitsTotal ? $this->percent($funnelLeadsTotal, $funnelVisitsTotal).'% of funnel visits convert' : 'No funnel visits yet'),
                [
                    'label' => 'Awaiting reply',
                    'value' => Lead::where('status', 'new')->count(),
                    'delta' => null,
                    'note' => $oldestWaiting ? 'Oldest waiting '.$oldestWaiting->diffForHumans(null, true) : 'Everyone has a reply',
                    'spark' => $this->series($current->where('status', 'new'), $buckets),
                    'accent' => 'gold',
                ],
            ],
            'flow' => [
                'labels' => array_column($buckets, 'label'),
                'website' => $this->series($current->reject($isFunnel), $buckets),
                'funnels' => $this->series($current->filter($isFunnel), $buckets),
                'total' => $current->count(),
                'delta' => $this->delta($current->count(), $previous->count()),
            ],
            'pipeline' => $this->pipeline(),
            'requestTypes' => $this->requestTypes($current),
            'residences' => $this->residences($current),
            'sources' => $this->sources($current),
            'heatmap' => $this->heatmap($current),
            'funnels' => Funnel::withCount('leads')->orderByDesc('leads_count')->orderByDesc('visits')->limit(6)->get(),
            'upcoming' => Lead::whereIn('form_type', LeadController::VISIT_TYPES)
                ->whereDate('preferred_date', '>=', $now->toDateString())
                ->whereNotIn('status', ['lost'])
                ->orderBy('preferred_date')
                ->limit(5)
                ->get(),
            'visitsWeek' => Lead::whereIn('form_type', LeadController::VISIT_TYPES)
                ->whereBetween('preferred_date', [$now->toDateString(), $now->addDays(6)->toDateString()])
                ->whereNotIn('status', ['lost'])
                ->count(),
            'recent' => Lead::with('funnel')->latest()->limit(6)->get(),
            'studio' => [
                'media' => Media::count(),
                'mediaBytes' => (int) Media::sum('size'),
                'formsActive' => LeadForm::where('is_active', true)->count(),
                'formsTotal' => LeadForm::count(),
                'funnelsActive' => Funnel::where('is_active', true)->count(),
                'funnelsTotal' => Funnel::count(),
                'lastEdit' => ContentBlock::max('updated_at'),
            ],
        ]);
    }

    /**
     * @return array{0: CarbonImmutable, 1: CarbonImmutable}
     */
    private function window(string $range, CarbonImmutable $now): array
    {
        if ($range === '12m') {
            $start = $now->startOfMonth()->subMonths(11);

            return [$start, $start->subMonths(12)];
        }

        $days = self::RANGES[$range]['days'];
        $start = $now->startOfDay()->subDays($days - 1);

        return [$start, $start->subDays($days)];
    }

    /**
     * @return list<array{key: string, label: string}>
     */
    private function buckets(string $range, CarbonImmutable $start, CarbonImmutable $now): array
    {
        $bucket = self::RANGES[$range]['bucket'];
        $cursor = match ($bucket) {
            'week' => $start->startOfWeek(),
            'month' => $start->startOfMonth(),
            default => $start,
        };

        $buckets = [];
        while ($cursor->lte($now)) {
            $buckets[] = [
                'key' => $this->bucketKey($cursor, $bucket),
                'label' => $cursor->format($bucket === 'month' ? 'M Y' : 'j M'),
                'bucket' => $bucket,
            ];
            $cursor = match ($bucket) {
                'week' => $cursor->addWeek(),
                'month' => $cursor->addMonth(),
                default => $cursor->addDay(),
            };
        }

        return $buckets;
    }

    private function bucketKey(\DateTimeInterface $date, string $bucket): string
    {
        $date = CarbonImmutable::instance($date);

        return match ($bucket) {
            'week' => $date->startOfWeek()->format('Y-m-d'),
            'month' => $date->format('Y-m'),
            default => $date->format('Y-m-d'),
        };
    }

    /**
     * @return list<int>
     */
    private function series(Collection $leads, array $buckets): array
    {
        if ($buckets === []) {
            return [];
        }

        $bucket = $buckets[0]['bucket'];
        $counts = $leads->countBy(fn (Lead $lead) => $this->bucketKey($lead->local_at, $bucket));

        return array_map(fn ($b) => (int) ($counts[$b['key']] ?? 0), $buckets);
    }

    private function kpi(string $label, Collection $current, Collection $previous, array $buckets, callable $filter, string $note): array
    {
        $now = $current->filter($filter);
        $before = $previous->filter($filter)->count();

        return [
            'label' => $label,
            'value' => $now->count(),
            'delta' => $this->delta($now->count(), $before),
            'note' => $note,
            'spark' => $this->series($now, $buckets),
            'accent' => 'navy',
        ];
    }

    /**
     * Percentage change against the previous period, or null when there is nothing to compare with.
     */
    private function delta(int $current, int $previous): ?int
    {
        if ($previous === 0) {
            return null;
        }

        return (int) round(($current - $previous) / $previous * 100);
    }

    private function percent(int $part, int $whole): float
    {
        return $whole > 0 ? round($part / $whole * 100, 1) : 0.0;
    }

    private function pipeline(): array
    {
        $counts = Lead::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $won = (int) ($counts['won'] ?? 0);
        $closed = $won + (int) ($counts['lost'] ?? 0);

        return [
            'labels' => array_values(Lead::STATUSES),
            'values' => array_map(fn ($status) => (int) ($counts[$status] ?? 0), array_keys(Lead::STATUSES)),
            'colours' => array_values(self::STATUS_COLOURS),
            'keys' => array_keys(Lead::STATUSES),
            'total' => (int) $counts->sum(),
            'open' => (int) (($counts['new'] ?? 0) + ($counts['contacted'] ?? 0) + ($counts['visit_scheduled'] ?? 0)),
            'winRate' => $closed ? $this->percent($won, $closed) : null,
        ];
    }

    private function requestTypes(Collection $leads): array
    {
        $short = [
            'book-visit' => 'Site visit',
            'schedule-visit' => 'Virtual visit',
            'purchase' => 'Purchase',
            'price-list' => 'Price list',
            'investment-pack' => 'Investment pack',
            'general' => 'General enquiry',
        ];
        $counts = $leads->countBy('form_type');
        $rows = collect(LeadFormTypes::types())
            ->map(fn ($type, $key) => ['label' => $short[$key] ?? $type['label'], 'value' => (int) ($counts[$key] ?? 0)])
            ->sortByDesc('value')
            ->values();

        return ['labels' => $rows->pluck('label')->all(), 'values' => $rows->pluck('value')->all()];
    }

    private function residences(Collection $leads): array
    {
        $counts = $leads
            ->map(fn (Lead $lead) => $lead->residence_type ?: (array_key_exists((string) $lead->interest, LeadFormTypes::RESIDENCES) ? $lead->interest : null))
            ->filter()
            ->countBy();

        $types = array_diff_key(LeadFormTypes::RESIDENCES, ['undecided' => true]);

        return [
            'labels' => array_values($types),
            'values' => array_map(fn ($key) => (int) ($counts[$key] ?? 0), array_keys($types)),
            'undecided' => (int) ($counts['undecided'] ?? 0),
        ];
    }

    /**
     * @return list<array{label: string, value: int, share: float}>
     */
    private function sources(Collection $leads): array
    {
        $total = max(1, $leads->count());

        return $leads
            ->countBy(fn (Lead $lead) => $lead->utm_source
                ? (Funnel::CHANNELS[strtolower($lead->utm_source)] ?? ucfirst($lead->utm_source))
                : 'Website, direct')
            ->sortDesc()
            ->take(6)
            ->map(fn ($value, $label) => ['label' => $label, 'value' => $value, 'share' => round($value / $total * 100, 1)])
            ->values()
            ->all();
    }

    /**
     * Enquiries by weekday and three-hour band, in the site timezone.
     */
    private function heatmap(Collection $leads): array
    {
        $grid = array_fill(0, 7, array_fill(0, 8, 0));
        foreach ($leads as $lead) {
            $grid[$lead->local_at->dayOfWeekIso - 1][intdiv($lead->local_at->hour, 3)]++;
        }

        $max = max(1, max(array_map('max', $grid)));
        $peak = null;
        foreach ($grid as $day => $bands) {
            foreach ($bands as $band => $count) {
                if ($count > 0 && ($peak === null || $count > $peak['count'])) {
                    $peak = ['day' => $day, 'band' => $band, 'count' => $count];
                }
            }
        }

        return [
            'days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'bands' => ['00', '03', '06', '09', '12', '15', '18', '21'],
            'grid' => $grid,
            'max' => $max,
            'peak' => $peak,
        ];
    }
}
