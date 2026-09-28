@php
    use App\Support\LeadFormTypes;
    use Illuminate\Support\Number;
    use Illuminate\Support\Str;

    $awaiting = $kpis[3]['value'];
    $rangeLabel = $ranges[$range]['label'];
@endphp

<x-layouts.admin title="Dashboard" kicker="Operations">
    <x-slot:actions>
        <a href="{{ route('admin.leads.export') }}" class="adm-btn adm-btn--ghost adm-btn--sm hidden md:inline-flex">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 19h14" stroke="currentColor" stroke-width="1.6"/></svg>
            Export leads
        </a>
    </x-slot:actions>

    <div class="space-y-6">
        {{-- Welcome --}}
        <section class="adm-hero adm-rise">
            <img src="{{ asset('media/tower-dusk.webp') }}" alt="" class="adm-hero__still">
            <span class="adm-hero__frame" aria-hidden="true"></span>
            <div class="relative grid gap-10 p-8 lg:grid-cols-[1.5fr_1fr] lg:items-end lg:p-10">
                <div>
                    <p class="adm-kicker">{{ $today->format('l, j F Y') }}</p>
                    <h2 class="adm-title adm-title--light mt-4 text-4xl sm:text-5xl">Good <em class="text-[#e3c992]">{{ $daypart }}.</em></h2>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#e8e9ea]/75">
                        @if ($awaiting || $visitsWeek)
                            {{ $awaiting }} {{ Str::plural('enquiry', $awaiting) }} {{ $awaiting === 1 ? 'awaits' : 'await' }} a reply, and {{ $visitsWeek }} site {{ Str::plural('visit', $visitsWeek) }} {{ $visitsWeek === 1 ? 'is' : 'are' }} requested for the next seven days.
                        @else
                            Every enquiry has a reply and there are no site visits in the next seven days.
                        @endif
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('admin.leads.index', ['status' => 'new']) }}" class="adm-btn adm-btn--gold adm-btn--sm">Reply to new leads</a>
                        <a href="{{ route('admin.leads.visits') }}" class="adm-btn adm-btn--ghost-light adm-btn--ghost adm-btn--sm">Visit calendar</a>
                        <a href="{{ route('admin.funnels.create') }}" class="adm-btn adm-btn--ghost-light adm-btn--ghost adm-btn--sm">New funnel</a>
                    </div>
                </div>
                <dl class="grid grid-cols-3 border-t border-[#bca869]/25 pt-6 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
                    <div>
                        <dt class="adm-kicker">Open pipeline</dt>
                        <dd class="adm-hero__figure">{{ $pipeline['open'] }}</dd>
                    </div>
                    <div>
                        <dt class="adm-kicker">Win rate</dt>
                        <dd class="adm-hero__figure">{{ $pipeline['winRate'] !== null ? $pipeline['winRate'].'%' : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="adm-kicker">Visits, 7 days</dt>
                        <dd class="adm-hero__figure">{{ $visitsWeek }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- Range --}}
        <div class="flex flex-wrap items-end justify-between gap-4 pt-2">
            <div>
                <p class="adm-kicker">Performance</p>
                <p class="adm-title mt-2 text-3xl">The last {{ $rangeLabel }}</p>
            </div>
            <nav class="adm-segment" aria-label="Reporting period">
                @foreach ($ranges as $key => $option)
                    <a href="{{ route('admin.dashboard', ['range' => $key]) }}" @class(['is-active' => $key === $range]) @if ($key === $range) aria-current="page" @endif>{{ $option['label'] }}</a>
                @endforeach
            </nav>
        </div>

        {{-- KPIs --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kpis as $kpi)
                <article class="adm-kpi {{ $kpi['accent'] === 'gold' ? 'adm-kpi--gold' : '' }}">
                    <div class="flex items-start justify-between gap-3 px-6 pt-6">
                        <p class="adm-kicker adm-kicker--navy">{{ $kpi['label'] }}</p>
                        @if ($kpi['delta'] !== null)
                            <span @class(['adm-delta', 'is-up' => $kpi['delta'] > 0, 'is-down' => $kpi['delta'] < 0]) title="Compared with the previous {{ $rangeLabel }}">
                                @if ($kpi['delta'] > 0)
                                    <svg viewBox="0 0 12 12" fill="none"><path d="M6 10V2M2.5 5.5 6 2l3.5 3.5" stroke="currentColor" stroke-width="1.3"/></svg>
                                @elseif ($kpi['delta'] < 0)
                                    <svg viewBox="0 0 12 12" fill="none"><path d="M6 2v8M2.5 6.5 6 10l3.5-3.5" stroke="currentColor" stroke-width="1.3"/></svg>
                                @endif
                                {{ abs($kpi['delta']) }}%
                            </span>
                        @endif
                    </div>
                    <p class="adm-kpi__value px-6">{{ number_format($kpi['value']) }}</p>
                    <p class="mt-2 px-6 text-xs text-[#6f675e]">{{ $kpi['note'] }}</p>
                    <div class="adm-kpi__spark" x-data="apexChart('spark', @js(['values' => $kpi['spark'], 'labels' => $flow['labels'], 'name' => $kpi['label'], 'gold' => $kpi['accent'] === 'gold']))"></div>
                </article>
            @endforeach
        </section>

        {{-- Lead flow + pipeline --}}
        <section class="grid gap-6 xl:grid-cols-3">
            <article class="adm-card xl:col-span-2">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Lead flow</p>
                        <h3 class="adm-title mt-1.5 text-2xl">Enquiries by source</h3>
                    </div>
                    <div class="flex items-center gap-5 text-xs text-[#6f675e]">
                        <span class="adm-legend"><i style="background:#0e1e37"></i>Website</span>
                        <span class="adm-legend"><i style="background:#d2ad65"></i>Social funnels</span>
                    </div>
                </header>
                <div class="flex flex-wrap items-baseline gap-x-4 gap-y-1 px-6 pt-5">
                    <span class="font-serif text-4xl leading-none text-[#0e1e37]">{{ number_format($flow['total']) }}</span>
                    <span class="text-xs text-[#6f675e]">
                        enquiries in the last {{ $rangeLabel }}
                        @if ($flow['delta'] !== null)
                            <span @class(['ml-1', 'text-[#2f6b4f]' => $flow['delta'] >= 0, 'text-[#9c3b2e]' => $flow['delta'] < 0])>{{ $flow['delta'] >= 0 ? '+' : '−' }}{{ abs($flow['delta']) }}% on the previous period</span>
                        @endif
                    </span>
                </div>
                <div class="h-80 px-3 pb-3" x-data="apexChart('flow', @js($flow))"></div>
            </article>

            <article class="adm-card flex flex-col">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Pipeline</p>
                        <h3 class="adm-title mt-1.5 text-2xl">Every lead, by stage</h3>
                    </div>
                </header>
                @if ($pipeline['total'])
                    <div class="relative mx-auto mt-4 h-60 w-full max-w-[16rem]">
                        <div class="h-full" x-data="apexChart('donut', @js($pipeline))"></div>
                        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                            <span class="font-serif text-5xl leading-none text-[#0e1e37]">{{ number_format($pipeline['total']) }}</span>
                            <span class="adm-kicker mt-2">Leads</span>
                        </div>
                    </div>
                    <ul class="mt-auto divide-y divide-[#efe9df] border-t border-[#efe9df]">
                        @foreach ($pipeline['keys'] as $i => $status)
                            <li>
                                <a href="{{ route('admin.leads.index', ['status' => $status]) }}" class="flex items-center justify-between px-6 py-2.5 text-sm transition hover:bg-[#fcfbf8]">
                                    <span class="adm-legend"><i style="background:{{ $pipeline['colours'][$i] }}"></i>{{ $pipeline['labels'][$i] }}</span>
                                    <span class="tabular-nums text-[#6f675e]">{{ $pipeline['values'][$i] }}<span class="ml-3 inline-block w-10 text-right text-xs">{{ $pipeline['total'] ? round($pipeline['values'][$i] / $pipeline['total'] * 100) : 0 }}%</span></span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="adm-empty flex-1">
                        <p class="adm-title text-2xl">No leads yet.</p>
                        <p>The pipeline fills as enquiries arrive.</p>
                    </div>
                @endif
            </article>
        </section>

        {{-- Demand --}}
        <section class="grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
            <article class="adm-card">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Requests</p>
                        <h3 class="adm-title mt-1.5 text-2xl">What buyers ask for</h3>
                    </div>
                </header>
                <div class="h-72 px-2 pb-2" x-data="apexChart('bars', @js($requestTypes))"></div>
            </article>

            <article class="adm-card">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Demand</p>
                        <h3 class="adm-title mt-1.5 text-2xl">Residence interest</h3>
                    </div>
                    @if ($residences['undecided'])
                        <span class="text-xs text-[#6f675e]">{{ $residences['undecided'] }} undecided</span>
                    @endif
                </header>
                <div class="h-72 px-2 pb-2" x-data="apexChart('columns', @js($residences))"></div>
            </article>

            <article class="adm-card lg:col-span-2 xl:col-span-1">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Attribution</p>
                        <h3 class="adm-title mt-1.5 text-2xl">Top sources</h3>
                    </div>
                </header>
                @if ($sources)
                    <ul class="space-y-5 px-6 py-6">
                        @foreach ($sources as $i => $source)
                            <li>
                                <div class="flex items-baseline justify-between gap-3 text-sm">
                                    <span class="flex items-baseline gap-3">
                                        <span class="font-serif text-sm italic text-[#bca869]">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        {{ $source['label'] }}
                                    </span>
                                    <span class="tabular-nums text-[#6f675e]">{{ $source['value'] }} <span class="ml-2 text-xs">{{ $source['share'] }}%</span></span>
                                </div>
                                <div class="adm-meter mt-2"><span style="width: {{ max(2, $source['share']) }}%"></span></div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="adm-empty">
                        <p class="adm-title text-2xl">No sources yet.</p>
                        <p>Nothing arrived in the last {{ $rangeLabel }}.</p>
                    </div>
                @endif
            </article>
        </section>

        {{-- Timing + visits --}}
        <section class="grid gap-6 xl:grid-cols-3">
            <article class="adm-card xl:col-span-2">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Timing</p>
                        <h3 class="adm-title mt-1.5 text-2xl">When enquiries arrive</h3>
                    </div>
                    @if ($heatmap['peak'])
                        <p class="text-right text-xs text-[#6f675e]">
                            Busiest: <span class="text-[#0e1e37]">{{ $heatmap['days'][$heatmap['peak']['day']] }}, {{ $heatmap['bands'][$heatmap['peak']['band']] }}:00 to {{ str_pad(($heatmap['peak']['band'] + 1) * 3 % 24, 2, '0', STR_PAD_LEFT) }}:00</span>
                        </p>
                    @endif
                </header>
                <div class="overflow-x-auto px-6 py-6">
                    <div class="adm-heat min-w-[34rem]">
                        <span></span>
                        @foreach ($heatmap['bands'] as $band)
                            <span class="adm-heat__axis">{{ $band }}:00</span>
                        @endforeach
                        @foreach ($heatmap['grid'] as $day => $bands)
                            <span class="adm-heat__axis adm-heat__axis--row">{{ $heatmap['days'][$day] }}</span>
                            @foreach ($bands as $band => $count)
                                <span class="adm-heat__cell" style="--heat: {{ $count ? round(0.12 + 0.88 * $count / $heatmap['max'], 3) : 0 }}"
                                    data-tip="{{ $count }} {{ Str::plural('enquiry', $count) }} · {{ $heatmap['days'][$day] }} {{ $heatmap['bands'][$band] }}:00"></span>
                            @endforeach
                        @endforeach
                    </div>
                    <div class="mt-5 flex items-center justify-end gap-2 text-[0.65rem] uppercase tracking-[0.18em] text-[#6f675e]">
                        Fewer
                        @foreach ([0, 0.25, 0.5, 0.75, 1] as $step)
                            <span class="adm-heat__cell adm-heat__cell--key" style="--heat: {{ $step }}"></span>
                        @endforeach
                        More
                    </div>
                    <p class="mt-2 text-right text-[0.7rem] text-[#6f675e]">Times shown in {{ str_replace('_', ' ', config('site.timezone')) }}.</p>
                </div>
            </article>

            <article class="adm-card flex flex-col">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Agenda</p>
                        <h3 class="adm-title mt-1.5 text-2xl">Upcoming visits</h3>
                    </div>
                    <a href="{{ route('admin.leads.visits') }}" class="adm-link">Calendar</a>
                </header>
                @forelse ($upcoming as $visit)
                    <a href="{{ route('admin.leads.show', $visit) }}" class="adm-agenda">
                        <span class="adm-agenda__date">
                            <span>{{ $visit->preferred_date->format('M') }}</span>
                            <strong>{{ $visit->preferred_date->format('j') }}</strong>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate font-serif text-lg leading-tight text-[#161311]">{{ $visit->name }}</span>
                            <span class="block truncate text-xs text-[#6f675e]">
                                {{ $visit->preferred_date->isToday() ? 'Today' : ($visit->preferred_date->isTomorrow() ? 'Tomorrow' : $visit->preferred_date->format('l')) }}{{ $visit->preferred_time ? ', '.Str::lower($visit->preferred_time) : '' }}
                                · {{ $visit->visit_type === 'virtual' ? 'Virtual' : 'On site' }}{{ $visit->guests ? ' · '.$visit->guests.' '.Str::plural('guest', $visit->guests) : '' }}
                            </span>
                        </span>
                        <span class="adm-pill adm-pill--{{ $visit->status }}">{{ $visit->statusLabel() }}</span>
                    </a>
                @empty
                    <div class="adm-empty flex-1">
                        <p class="adm-title text-2xl">A quiet calendar.</p>
                        <p>No upcoming site visits have been requested.</p>
                    </div>
                @endforelse
            </article>
        </section>

        {{-- Funnels + studio --}}
        <section class="grid gap-6 xl:grid-cols-3">
            <article class="adm-card overflow-hidden xl:col-span-2">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Social funnels</p>
                        <h3 class="adm-title mt-1.5 text-2xl">Campaign performance</h3>
                    </div>
                    <a href="{{ route('admin.funnels.index') }}" class="adm-link">All funnels</a>
                </header>
                @if ($funnels->isEmpty())
                    <div class="adm-empty">
                        <p class="adm-title text-2xl">No funnels yet.</p>
                        <p>Create a landing page for Instagram, TikTok or WhatsApp to track sign-ups.</p>
                        <a href="{{ route('admin.funnels.create') }}" class="adm-btn adm-btn--sm mt-5">Create a funnel</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="adm-table">
                            <thead>
                                <tr>
                                    <th>Funnel</th>
                                    <th class="text-right">Visits</th>
                                    <th class="text-right">Leads</th>
                                    <th class="w-48">Conversion</th>
                                    <th class="text-right">Status</th>
                                </tr>
                            </thead>
                            @php
                                $rates = $funnels->mapWithKeys(fn ($f) => [$f->id => $f->visits ? min(100, round($f->leads_count / $f->visits * 100, 1)) : 0]);
                                $bestRate = max(1, $rates->max());
                            @endphp
                            <tbody>
                                @foreach ($funnels as $funnel)
                                    @php $rate = $rates[$funnel->id]; @endphp
                                    <tr class="cursor-pointer" onclick="window.location='{{ route('admin.funnels.edit', $funnel) }}'">
                                        <td>
                                            <span class="block font-serif text-lg leading-tight text-[#161311]">{{ $funnel->name }}</span>
                                            <span class="block text-xs text-[#6f675e]">{{ $funnel->channelLabel() }}</span>
                                        </td>
                                        <td class="text-right tabular-nums">{{ number_format($funnel->visits) }}</td>
                                        <td class="text-right tabular-nums">{{ number_format($funnel->leads_count) }}</td>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="adm-meter flex-1" title="Relative to the best-converting funnel"><span style="width: {{ max(2, $rate / $bestRate * 100) }}%"></span></div>
                                                <span class="w-12 text-right text-xs tabular-nums text-[#0e1e37]">{{ $rate }}%</span>
                                            </div>
                                        </td>
                                        <td class="text-right"><span class="adm-pill {{ $funnel->is_active ? 'adm-pill--on' : 'adm-pill--off' }}">{{ $funnel->is_active ? 'Live' : 'Paused' }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </article>

            <article class="adm-studio">
                <p class="adm-kicker">Content studio</p>
                <h3 class="adm-title adm-title--light mt-2 text-2xl">The house, at a glance</h3>
                <dl class="mt-6 divide-y divide-[#bca869]/15">
                    <div class="adm-studio__row">
                        <dt>Media library</dt>
                        <dd>{{ number_format($studio['media']) }} files <span>{{ Number::fileSize($studio['mediaBytes'], precision: 1) }}</span></dd>
                    </div>
                    <div class="adm-studio__row">
                        <dt>Lead forms live</dt>
                        <dd>{{ $studio['formsActive'] }} <span>of {{ $studio['formsTotal'] }}</span></dd>
                    </div>
                    <div class="adm-studio__row">
                        <dt>Funnels live</dt>
                        <dd>{{ $studio['funnelsActive'] }} <span>of {{ $studio['funnelsTotal'] }}</span></dd>
                    </div>
                    <div class="adm-studio__row">
                        <dt>Last page edit</dt>
                        <dd>{{ $studio['lastEdit'] ? \Illuminate\Support\Carbon::parse($studio['lastEdit'])->diffForHumans() : 'Not yet edited' }}</dd>
                    </div>
                </dl>
                <div class="mt-auto grid grid-cols-2 gap-3 pt-8">
                    <a href="{{ route('admin.website.index') }}" class="adm-btn adm-btn--ghost adm-btn--ghost-light adm-btn--sm">Edit pages</a>
                    <a href="{{ route('admin.media.index') }}" class="adm-btn adm-btn--gold adm-btn--sm">Media</a>
                </div>
            </article>
        </section>

        {{-- Recent leads --}}
        <section class="adm-card overflow-hidden">
            <header class="adm-card__head">
                <div>
                    <p class="adm-kicker">Latest</p>
                    <h3 class="adm-title mt-1.5 text-2xl">Recent enquiries</h3>
                </div>
                <a href="{{ route('admin.leads.index') }}" class="adm-link">All leads</a>
            </header>
            @if ($recent->isEmpty())
                <div class="adm-empty">
                    <p class="adm-title text-2xl">No enquiries yet.</p>
                    <p>New enquiries appear here the moment they are submitted.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Request</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th class="text-right">Received</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recent as $lead)
                                <tr class="cursor-pointer" onclick="window.location='{{ route('admin.leads.show', $lead) }}'">
                                    <td>
                                        <a href="{{ route('admin.leads.show', $lead) }}" class="flex items-center gap-3">
                                            <span class="adm-avatar">{{ Str::upper(Str::substr($lead->name, 0, 1)) }}</span>
                                            <span class="min-w-0">
                                                <span class="block truncate font-serif text-lg leading-tight text-[#161311]">{{ $lead->name }}</span>
                                                <span class="block truncate text-xs text-[#6f675e]">{{ $lead->email }}</span>
                                            </span>
                                        </a>
                                    </td>
                                    <td class="text-sm">{{ LeadFormTypes::label($lead->form_type) }}</td>
                                    <td class="text-sm">{{ $lead->funnel?->name ?? 'Website' }}</td>
                                    <td><span class="adm-pill adm-pill--{{ $lead->status }}">{{ $lead->statusLabel() }}</span></td>
                                    <td class="whitespace-nowrap text-right text-xs text-[#6f675e]" title="{{ $lead->created_at->format('j F Y, H:i') }}">{{ $lead->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
