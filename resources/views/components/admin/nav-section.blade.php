@props(['title'])

<div {{ $attributes->merge(['class' => 'px-3 pt-5 pb-2 first:pt-0']) }}>
    <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">{{ $title }}</p>
</div>
