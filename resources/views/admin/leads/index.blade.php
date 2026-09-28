<x-layouts.admin title="Leads" kicker="Leads">
    <x-slot:actions>
        <a href="{{ route('admin.leads.export', request()->query()) }}" class="adm-btn adm-btn--sm">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 19h14" stroke="currentColor" stroke-width="1.6"/></svg>
            Export CSV
        </a>
    </x-slot:actions>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">All leads</p><p class="adm-stat__value">{{ $stats['total'] }}</p></div>
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Awaiting reply</p><p class="adm-stat__value">{{ $stats['new'] }}</p></div>
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Last seven days</p><p class="adm-stat__value">{{ $stats['week'] }}</p></div>
        <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">From social funnels</p><p class="adm-stat__value">{{ $stats['funnel'] }}</p></div>
    </div>

    <div class="mt-8 flex flex-wrap items-center justify-between gap-4">
        <div class="adm-tabs">
            <a href="{{ route('admin.leads.index', array_merge($filters, ['status' => null])) }}" @class(['adm-tab', 'is-active' => empty($filters['status'])])>All <span>{{ $stats['total'] }}</span></a>
            @foreach (\App\Models\Lead::STATUSES as $value => $label)
                <a href="{{ route('admin.leads.index', array_merge($filters, ['status' => $value])) }}" @class(['adm-tab', 'is-active' => ($filters['status'] ?? null) === $value])>{{ $label }} <span>{{ $statusCounts[$value] ?? 0 }}</span></a>
            @endforeach
        </div>
    </div>

    <form method="GET" class="adm-card mt-5 grid gap-3 p-4 md:grid-cols-[1.4fr_1fr_1fr_auto]">
        @if (! empty($filters['status']))
            <input type="hidden" name="status" value="{{ $filters['status'] }}">
        @endif
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="adm-input" placeholder="Search name, email or telephone" aria-label="Search leads">
        <select name="type" class="adm-select" aria-label="Form type">
            <option value="">Every form type</option>
            @foreach (\App\Support\LeadFormTypes::types() as $value => $type)
                <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $type['label'] }}</option>
            @endforeach
        </select>
        <select name="funnel" class="adm-select" aria-label="Source">
            <option value="">Every source</option>
            <option value="website" @selected(($filters['funnel'] ?? '') === 'website')>Website only</option>
            @foreach ($funnels as $funnel)
                <option value="{{ $funnel->id }}" @selected((string) ($filters['funnel'] ?? '') === (string) $funnel->id)>{{ $funnel->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button type="submit" class="adm-btn">Filter</button>
            @if (array_filter($filters))
                <a href="{{ route('admin.leads.index') }}" class="adm-btn adm-btn--ghost">Clear</a>
            @endif
        </div>
    </form>

    <div class="adm-card mt-5 overflow-x-auto">
        @if ($leads->isEmpty())
            <div class="py-20 text-center">
                <p class="adm-title text-3xl">No leads yet.</p>
                <p class="mt-3 text-sm text-[#6f675e]">Enquiries, site visit requests and funnel sign-ups will appear here as they arrive.</p>
            </div>
        @else
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
                    @foreach ($leads as $lead)
                        <tr class="cursor-pointer" onclick="window.location='{{ route('admin.leads.show', $lead) }}'">
                            <td>
                                <a href="{{ route('admin.leads.show', $lead) }}" class="block">
                                    <span class="block font-serif text-lg leading-tight text-[#161311]">{{ $lead->name }}</span>
                                    <span class="block text-xs text-[#6f675e]">{{ $lead->email }}</span>
                                </a>
                            </td>
                            <td>
                                <span class="block text-sm">{{ \App\Support\LeadFormTypes::label($lead->form_type) }}</span>
                                @if ($lead->preferred_date)
                                    <span class="block text-xs text-[#6f675e]">{{ $lead->preferred_date->format('j M Y') }}{{ $lead->preferred_time ? ', '.$lead->preferred_time : '' }}</span>
                                @elseif ($lead->interest)
                                    <span class="block text-xs text-[#6f675e]">{{ \App\Support\SiteContent::interests()[$lead->interest] ?? $lead->interest }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="block text-sm">{{ $lead->funnel?->name ?? 'Website' }}</span>
                                @if ($lead->utm_source)
                                    <span class="block text-xs text-[#6f675e]">{{ $lead->utm_source }}{{ $lead->utm_campaign ? ' / '.$lead->utm_campaign : '' }}</span>
                                @endif
                            </td>
                            <td><span class="adm-pill adm-pill--{{ $lead->status }}">{{ $lead->statusLabel() }}</span></td>
                            <td class="whitespace-nowrap text-right text-xs text-[#6f675e]" title="{{ $lead->created_at->format('j F Y, H:i') }}">{{ $lead->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if ($leads->hasPages())
        <div class="mt-6">{{ $leads->links() }}</div>
    @endif
</x-layouts.admin>
