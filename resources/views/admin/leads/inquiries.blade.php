<x-layouts.admin title="Inquiries & Leads">
    <div class="space-y-6">
        <div class="admin-card p-5">
            <h2 class="text-lg font-semibold text-neutral-900">Lead Inbox</h2>
            <p class="mt-1 text-sm text-neutral-500">Central inbox for contact forms, site visit requests, and brochure download submissions.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            @foreach ([
                ['label' => 'New Today', 'value' => '14'],
                ['label' => 'Contact Forms', 'value' => '38'],
                ['label' => 'Site Visit Requests', 'value' => '21'],
                ['label' => 'Brochure Downloads', 'value' => '67'],
            ] as $stat)
                <article class="admin-card p-4">
                    <p class="text-xs text-neutral-500">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-2xl font-semibold text-neutral-900">{{ $stat['value'] }}</p>
                </article>
            @endforeach
        </div>

        <section class="admin-card overflow-hidden">
            <div class="flex items-center justify-between border-b border-neutral-200 px-5 py-4">
                <div class="flex gap-2">
                    <button class="rounded-lg bg-neutral-900 px-3 py-1.5 text-xs font-medium text-white">All</button>
                    <button class="rounded-lg border border-neutral-200 px-3 py-1.5 text-xs font-medium text-neutral-600 hover:bg-neutral-50">Contact</button>
                    <button class="rounded-lg border border-neutral-200 px-3 py-1.5 text-xs font-medium text-neutral-600 hover:bg-neutral-50">Site Visit</button>
                    <button class="rounded-lg border border-neutral-200 px-3 py-1.5 text-xs font-medium text-neutral-600 hover:bg-neutral-50">Brochure</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Lead</th>
                            <th class="px-5 py-3">Source</th>
                            <th class="px-5 py-3">Project</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Received</th>
                            <th class="px-5 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 bg-white">
                        @foreach ([
                            ['name' => 'Amira Hassan', 'email' => 'amira@email.com', 'source' => 'Contact Form', 'project' => '—', 'status' => 'New', 'statusStyle' => 'bg-blue-100 text-blue-700', 'date' => '2 min ago'],
                            ['name' => 'David Okoro', 'email' => 'david@email.com', 'source' => 'Book Site Visit', 'project' => 'Aegean Crown Villas', 'status' => 'Contacted', 'statusStyle' => 'bg-amber-100 text-amber-800', 'date' => '1 hr ago'],
                            ['name' => 'Lisa Müller', 'email' => 'lisa@email.com', 'source' => 'Brochure Download', 'project' => 'Marina Heights', 'status' => 'Qualified', 'statusStyle' => 'bg-emerald-100 text-emerald-700', 'date' => '3 hrs ago'],
                        ] as $lead)
                            <tr class="hover:bg-neutral-50/60">
                                <td class="px-5 py-4">
                                    <p class="font-medium text-neutral-900">{{ $lead['name'] }}</p>
                                    <p class="text-xs text-neutral-500">{{ $lead['email'] }}</p>
                                </td>
                                <td class="px-5 py-4 text-neutral-600">{{ $lead['source'] }}</td>
                                <td class="px-5 py-4 text-neutral-600">{{ $lead['project'] }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $lead['statusStyle'] }}">{{ $lead['status'] }}</span>
                                </td>
                                <td class="px-5 py-4 text-neutral-600">{{ $lead['date'] }}</td>
                                <td class="px-5 py-4">
                                    <button class="text-sm font-medium text-neutral-700 hover:text-neutral-900">View</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.admin>
