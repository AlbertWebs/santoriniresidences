<x-layouts.admin title="Pages Manager">
    <div class="space-y-6">
        <div class="admin-card p-5">
            <h2 class="text-lg font-semibold text-neutral-900">Public Pages Content</h2>
            <p class="mt-1 text-sm text-neutral-500">Manage static copy for core website pages. Select a page to open the split-pane editor with live preview.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                [
                    'title' => 'About Us',
                    'description' => 'Company history, team profiles, core values, and mission statement.',
                    'route' => route('admin.cms.pages.about'),
                    'icon' => 'M12 4.5a7.5 7.5 0 1 0 0 15 7.5 7.5 0 0 0 0-15ZM12 8v4l2.5 1.5',
                ],
                [
                    'title' => 'Services',
                    'description' => 'Off-plan consulting, architectural advisory, property management, and resale services.',
                    'route' => route('admin.cms.pages.services'),
                    'icon' => 'M4 7h16M4 12h10M4 17h7',
                ],
                [
                    'title' => 'FAQs & Guides',
                    'description' => 'Accordion blocks for off-plan purchasing, staging payments, and escrow.',
                    'route' => route('admin.cms.pages.faqs'),
                    'icon' => 'M8 10h8M8 14h5M6 4h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z',
                ],
            ] as $page)
                <a href="{{ $page['route'] }}" class="admin-card group block p-5 transition hover:border-neutral-300 hover:shadow-md">
                    <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-100 text-neutral-600 transition group-hover:bg-neutral-900 group-hover:text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                            <path d="{{ $page['icon'] }}" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-neutral-900">{{ $page['title'] }}</h3>
                    <p class="mt-2 text-sm text-neutral-500">{{ $page['description'] }}</p>
                    <p class="mt-4 text-sm font-medium text-neutral-700 group-hover:text-neutral-900">Edit page &rarr;</p>
                </a>
            @endforeach
        </div>
    </div>
</x-layouts.admin>
