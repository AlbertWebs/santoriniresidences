@props([
    'title' => 'Content Editor',
    'description' => 'Edit content and preview the public page layout in real time.',
])

<div class="admin-card overflow-hidden" x-data="{{ $alpineData ?? '{}' }}">
    <div class="border-b border-neutral-200 px-5 py-4">
        <h2 class="text-lg font-semibold text-neutral-900">{{ $title }}</h2>
        <p class="mt-1 text-sm text-neutral-500">{{ $description }}</p>
    </div>

    <div class="grid min-h-[640px] lg:grid-cols-2">
        <div class="border-b border-neutral-200 p-5 lg:border-b-0 lg:border-r">
            <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-neutral-400">Editor</p>
            {{ $editor }}
        </div>

        <div class="bg-neutral-50 p-5">
            <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-neutral-400">Live Preview</p>
            <div class="mx-auto max-w-md overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">
                {{ $preview }}
            </div>
        </div>
    </div>
</div>
