<x-layouts.admin title="Social funnels" kicker="Forms & funnels">
    <x-slot:actions>
        <a href="{{ route('admin.funnels.create') }}" class="adm-btn adm-btn--sm">
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8"/></svg>
            New funnel
        </a>
    </x-slot:actions>

    @php($rate = fn ($leads, $visits) => $visits ? number_format($leads / $visits * 100, 1).'%' : '0%')

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Live funnels</p><p class="adm-stat__value">{{ $totals['live'] }}</p></div>
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Visits</p><p class="adm-stat__value">{{ number_format($totals['visits']) }}</p></div>
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Leads</p><p class="adm-stat__value">{{ number_format($totals['leads']) }}</p></div>
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Conversion</p><p class="adm-stat__value">{{ $rate($totals['leads'], $totals['visits']) }}</p></div>
    </div>

    <div class="mt-8 grid gap-8 xl:grid-cols-[1fr_20rem]">
        <div class="space-y-4">
            @forelse ($funnels as $funnel)
                <article class="adm-card p-6">
                    <div class="flex flex-wrap items-start justify-between gap-6">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="adm-pill">{{ $funnel->channelLabel() }}</span>
                                <span class="adm-pill {{ $funnel->is_active ? 'adm-pill--on' : 'adm-pill--off' }}">{{ $funnel->is_active ? 'Live' : 'Paused' }}</span>
                            </div>
                            <h2 class="adm-title mt-4 text-3xl"><a href="{{ route('admin.funnels.edit', $funnel) }}" class="hover:text-[#0e1e37]">{{ $funnel->name }}</a></h2>
                            <p class="mt-1 text-sm text-[#6f675e]">Opens the {{ $funnel->form?->title ?? 'missing' }} form</p>
                        </div>
                        <dl class="grid grid-cols-3 gap-8 text-right">
                            <div><dt class="adm-kicker adm-kicker--navy">Visits</dt><dd class="mt-2 font-serif text-3xl">{{ number_format($funnel->visits) }}</dd></div>
                            <div><dt class="adm-kicker adm-kicker--navy">Leads</dt><dd class="mt-2 font-serif text-3xl">{{ number_format($funnel->leads_count) }}</dd></div>
                            <div><dt class="adm-kicker adm-kicker--navy">Rate</dt><dd class="mt-2 font-serif text-3xl text-[#0e1e37]">{{ $rate($funnel->leads_count, $funnel->visits) }}</dd></div>
                        </dl>
                    </div>
                    <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-[#efe9df] pt-5">
                        <div class="min-w-0 flex-1" x-data="copyField(@js($funnel->shareUrl()))">
                            <div class="adm-copy">
                                <input type="text" readonly :value="text" aria-label="Share link for {{ $funnel->name }}">
                                <button type="button" @click="copy()" x-text="copied ? 'Copied' : 'Copy link'"></button>
                            </div>
                        </div>
                        <a href="{{ $funnel->shareUrl() }}" target="_blank" rel="noopener noreferrer" class="adm-btn adm-btn--ghost adm-btn--sm">Preview</a>
                        <a href="{{ route('admin.funnels.edit', $funnel) }}" class="adm-btn adm-btn--sm">Edit</a>
                    </div>
                </article>
            @empty
                <div class="adm-card py-20 text-center">
                    <p class="adm-title text-3xl">No funnels yet.</p>
                    <p class="mt-3 text-sm text-[#6f675e]">Create a link for your Instagram bio, a Facebook campaign or a WhatsApp broadcast.</p>
                    <a href="{{ route('admin.funnels.create') }}" class="adm-btn mt-6">Create a funnel</a>
                </div>
            @endforelse
        </div>

        <aside class="space-y-5">
            <div class="adm-card p-7">
                <p class="adm-kicker">By channel</p>
                <ul class="mt-5 space-y-4">
                    @forelse ($byChannel as $channel => $numbers)
                        <li>
                            <div class="flex items-baseline justify-between text-sm">
                                <span>{{ \App\Models\Funnel::CHANNELS[$channel] ?? $channel }}</span>
                                <span class="text-xs text-[#6f675e]">{{ $numbers['leads'] }} of {{ $numbers['visits'] }}</span>
                            </div>
                            <div class="adm-progress mt-2"><div class="adm-progress__bar" style="width: {{ $numbers['visits'] ? min(100, round($numbers['leads'] / $numbers['visits'] * 100)) : 0 }}%"></div></div>
                        </li>
                    @empty
                        <li class="text-sm text-[#6f675e]">Channel results appear once a funnel receives visits.</li>
                    @endforelse
                </ul>
            </div>
            <div class="adm-card p-7" style="background: linear-gradient(180deg, #0e1e37, #07152a); border-color: rgba(188,168,105,.35)">
                <p class="adm-kicker">How funnels work</p>
                <ol class="mt-5 space-y-3 text-sm leading-relaxed text-[#e8e9ea]/75">
                    <li>1. Each funnel is a private landing page with its own headline, image and form.</li>
                    <li>2. Paste its link into a social profile, story, advert or message.</li>
                    <li>3. Visits are counted, and every lead is tagged with the funnel and campaign.</li>
                </ol>
            </div>
        </aside>
    </div>
</x-layouts.admin>
