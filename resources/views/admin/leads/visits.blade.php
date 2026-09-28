<x-layouts.admin title="Site visits" kicker="Leads">
    <x-slot:actions>
        <a href="{{ route('admin.leads.export', ['type' => 'book-visit']) }}" class="adm-btn adm-btn--ghost adm-btn--sm">Export visits</a>
    </x-slot:actions>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">New requests</p><p class="adm-stat__value">{{ $stats['requested'] }}</p></div>
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Confirmed</p><p class="adm-stat__value">{{ $stats['scheduled'] }}</p></div>
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Next seven days</p><p class="adm-stat__value">{{ $stats['week'] }}</p></div>
    </div>

    <div class="mt-8 grid gap-8 xl:grid-cols-[1fr_24rem]">
        <section class="adm-card">
            <div class="adm-card__head">
                <div>
                    <p class="adm-kicker">Agenda</p>
                    <h2 class="adm-title mt-1 text-2xl">Upcoming visits</h2>
                </div>
                <a href="{{ route('visit.book') }}" target="_blank" rel="noopener noreferrer" class="adm-link">Booking page</a>
            </div>

            @forelse ($upcoming as $date => $visits)
                @php($day = \Illuminate\Support\Carbon::parse($date))
                <div class="grid gap-4 border-b border-[#efe9df] px-6 py-6 last:border-0 md:grid-cols-[7rem_1fr]">
                    <div>
                        <p class="font-serif text-4xl leading-none text-[#0e1e37]">{{ $day->format('j') }}</p>
                        <p class="adm-kicker mt-2">{{ $day->format('M Y') }}</p>
                        <p class="mt-1 text-xs text-[#6f675e]">{{ $day->isToday() ? 'Today' : ($day->isTomorrow() ? 'Tomorrow' : $day->format('l')) }}</p>
                    </div>
                    <ul class="space-y-2">
                        @foreach ($visits as $lead)
                            <li>
                                <a href="{{ route('admin.leads.show', $lead) }}" class="flex flex-wrap items-center justify-between gap-3 border border-[#efe9df] bg-[#fdfcfa] px-4 py-3 transition hover:border-[#d3c4a6]">
                                    <span>
                                        <span class="block font-serif text-lg leading-tight">{{ $lead->name }}</span>
                                        <span class="block text-xs text-[#6f675e]">{{ $lead->preferred_time ?: 'Time to confirm' }} · {{ $lead->form_type === 'schedule-visit' ? 'Virtual' : 'On site' }}{{ $lead->guests ? ' · '.$lead->guests.' '.\Illuminate\Support\Str::plural('guest', $lead->guests) : '' }}</span>
                                    </span>
                                    <span class="adm-pill adm-pill--{{ $lead->status }}">{{ $lead->statusLabel() }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <div class="px-6 py-20 text-center">
                    <p class="adm-title text-3xl">No visits on the calendar.</p>
                    <p class="mt-3 text-sm text-[#6f675e]">Requests from the Book a site visit page and funnels appear here by preferred date.</p>
                </div>
            @endforelse
        </section>

        <aside class="space-y-6">
            <section class="adm-card">
                <div class="adm-card__head"><h2 class="adm-title text-2xl">Awaiting a date</h2></div>
                @forelse ($undated as $lead)
                    <a href="{{ route('admin.leads.show', $lead) }}" class="flex items-center justify-between gap-3 border-b border-[#efe9df] px-6 py-4 last:border-0 hover:bg-[#fcfbf8]">
                        <span>
                            <span class="block text-sm">{{ $lead->name }}</span>
                            <span class="block text-xs text-[#6f675e]">{{ $lead->created_at->diffForHumans() }}</span>
                        </span>
                        <span class="adm-pill adm-pill--{{ $lead->status }}">{{ $lead->statusLabel() }}</span>
                    </a>
                @empty
                    <p class="px-6 py-6 text-sm text-[#6f675e]">Every request has a date.</p>
                @endforelse
            </section>

            <section class="adm-card">
                <div class="adm-card__head"><h2 class="adm-title text-2xl">Recent past visits</h2></div>
                @forelse ($past as $lead)
                    <a href="{{ route('admin.leads.show', $lead) }}" class="flex items-center justify-between gap-3 border-b border-[#efe9df] px-6 py-4 last:border-0 hover:bg-[#fcfbf8]">
                        <span>
                            <span class="block text-sm">{{ $lead->name }}</span>
                            <span class="block text-xs text-[#6f675e]">{{ $lead->preferred_date->format('j M Y') }}</span>
                        </span>
                        <span class="adm-pill adm-pill--{{ $lead->status }}">{{ $lead->statusLabel() }}</span>
                    </a>
                @empty
                    <p class="px-6 py-6 text-sm text-[#6f675e]">No past visits yet.</p>
                @endforelse
            </section>
        </aside>
    </div>
</x-layouts.admin>
