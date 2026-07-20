<x-layouts.admin title="Dashboard">
    <div class="space-y-6">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @php
                $stats = [
                    ['label' => 'Total Projects', 'value' => '18', 'meta' => '+3 this quarter'],
                    ['label' => 'Total Units', 'value' => '1,246', 'meta' => '92 pre-launch'],
                    ['label' => 'Revenue Settled', 'value' => '$48.9M', 'meta' => '+11.4% MoM'],
                    ['label' => 'Active Leads', 'value' => '326', 'meta' => '41 high intent'],
                ];
            @endphp
            @foreach ($stats as $stat)
                <article class="admin-card p-5">
                    <p class="text-sm text-neutral-500">{{ $stat['label'] }}</p>
                    <p class="mt-2 text-3xl font-semibold tracking-tight text-neutral-900">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-xs text-emerald-600">{{ $stat['meta'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="admin-card overflow-hidden">
            <div class="flex items-center justify-between border-b border-neutral-200 px-5 py-4">
                <h2 class="text-base font-semibold text-neutral-900">Recent Listings</h2>
                <a href="{{ route('admin.projects.create') }}" class="btn-primary">Create Project</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Project</th>
                            <th class="px-5 py-3">Location</th>
                            <th class="px-5 py-3">Units</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Completion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 bg-white">
                        @foreach ([
                            ['name' => 'Aegean Crown Villas', 'location' => 'North Coast', 'units' => 64, 'status' => 'Selling Fast', 'statusStyle' => 'bg-amber-100 text-amber-800', 'date' => 'Q2 2027'],
                            ['name' => 'Marina Heights', 'location' => 'West Bay', 'units' => 112, 'status' => 'Pre-Launch', 'statusStyle' => 'bg-blue-100 text-blue-700', 'date' => 'Q4 2028'],
                            ['name' => 'Azure Dunes Residences', 'location' => 'Palm District', 'units' => 48, 'status' => 'Sold Out', 'statusStyle' => 'bg-emerald-100 text-emerald-700', 'date' => 'Q1 2026'],
                        ] as $row)
                            <tr class="hover:bg-neutral-50/60">
                                <td class="px-5 py-4 font-medium text-neutral-900">{{ $row['name'] }}</td>
                                <td class="px-5 py-4 text-neutral-600">{{ $row['location'] }}</td>
                                <td class="px-5 py-4 text-neutral-600">{{ $row['units'] }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $row['statusStyle'] }}">{{ $row['status'] }}</span>
                                </td>
                                <td class="px-5 py-4 text-neutral-600">{{ $row['date'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.admin>
