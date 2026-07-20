@props([
    'active' => false,
])

<a {{ $attributes->merge([
    'class' => 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition ' .
        ($active
            ? 'bg-neutral-900 text-white shadow-sm'
            : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900'),
]) }}>
    <span class="{{ $active ? 'text-white' : 'text-neutral-400 group-hover:text-neutral-700' }}">
        {{ $icon }}
    </span>
    <span>{{ $slot }}</span>
</a>
