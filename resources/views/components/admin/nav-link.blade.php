@props([
    'active' => false,
    'badge' => null,
])

<a {{ $attributes->class(['adm-nav-link', 'is-active' => $active]) }} @if ($active) aria-current="page" @endif>
    {{ $icon }}
    <span>{{ $slot }}</span>
    @if ($badge)
        <span class="adm-nav-badge">{{ $badge }}</span>
    @endif
</a>
