@php
    $navigation = cms('settings.navigation');
    $onHome = request()->routeIs('home');
    $menu = [
        ['label' => 'The Landmark', 'href' => route('home').'#landmark', 'spy' => 'project,landmark,facade,distinctions'],
        ['label' => 'Residences', 'href' => route('residences'), 'spy' => 'residences', 'route' => 'residences'],
        ['label' => 'Experience', 'href' => route('home').'#experience', 'spy' => 'experience'],
        ['label' => 'Gallery', 'href' => route('gallery'), 'route' => 'gallery'],
        ['label' => 'The House', 'href' => route('about'), 'route' => 'about'],
        ['label' => 'Location', 'href' => route('home').'#location', 'spy' => 'location', 'mobile' => true],
    ];
    $isCurrent = fn (array $item) => isset($item['route']) && request()->routeIs($item['route']);
@endphp
<header class="fixed inset-x-0 top-0 z-50 transition-colors duration-500" :class="open ? 'border-b border-ink/10 bg-paper/90 text-ink shadow-lg shadow-ink/5 backdrop-blur-xl' : (solid ? 'border-b border-white/10 bg-navy/40 text-pearl shadow-lg shadow-black/10 backdrop-blur-xl' : 'border-b border-white/0 bg-transparent text-white')">
    <div class="mx-auto flex h-20 max-w-[1600px] items-center justify-between px-5 md:h-24 md:px-10">
        <a href="{{ route('home') }}" class="group leading-none" aria-label="Santorini Residences, home">
            <img
                src="{{ \App\Support\ResponsiveImage::url('media/logo-santorini-enhanced.png', 400) }}"
                width="1634" height="962"
                alt="Santorini Residences"
                class="brand-mark"
            >
        </a>

        <nav class="site-nav-primary hidden items-center gap-8 text-[0.68rem] tracking-[0.18em] uppercase lg:flex" aria-label="Primary">
            @foreach ($menu as $item)
                @continue($item['mobile'] ?? false)
                <a href="{{ $item['href'] }}" @class(['link-line', 'is-active' => $isCurrent($item)]) @if ($isCurrent($item)) aria-current="page" @endif @if ($onHome && isset($item['spy'])) data-spy="{{ $item['spy'] }}" @endif>{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-6">
            <x-cms-link :link="$navigation['visit']" class="link-line hidden text-[0.68rem] tracking-[0.18em] uppercase xl:inline-block {{ request()->routeIs('visit.book') ? 'is-active' : '' }}" />
            <x-cms-link :link="$navigation['cta']" class="site-button hidden md:inline-flex" x-bind:class="open ? '' : 'site-button-on-dark'" />
            <button type="button" class="menu-toggle h-11 w-11 items-center justify-center border border-current" :class="solid || open ? 'bg-transparent' : 'bg-ink/55 text-white'" @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobile-menu" aria-label="Menu">
                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 17h16" stroke="currentColor" stroke-width="1.2"/></svg>
                <svg x-show="open" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.2"/></svg>
            </button>
        </div>
    </div>
</header>

<div id="mobile-menu" x-show="open" x-cloak x-transition.opacity.duration.400ms class="fixed inset-0 z-40 bg-paper/90 text-ink backdrop-blur-xl lg:hidden" @keydown.escape.window="open = false">
    <nav class="flex h-full flex-col justify-end gap-4 overflow-y-auto px-6 pb-12 pt-28" aria-label="Mobile">
        @foreach ($menu as $item)
            <a href="{{ $item['href'] }}" @class(['mobile-link font-serif text-4xl sm:text-5xl', 'is-active' => $isCurrent($item)]) @if ($isCurrent($item)) aria-current="page" @endif @if ($onHome && isset($item['spy'])) data-spy="{{ $item['spy'] }}" @endif @click="open = false">{{ $item['label'] }}</a>
        @endforeach
        <div class="mt-6 flex flex-wrap items-center gap-x-8 gap-y-5">
            <x-cms-link :link="$navigation['cta']" class="site-button w-fit" x-on:click="open = false" />
            <x-cms-link :link="$navigation['visit']" class="link-gold link-gold--navy" x-on:click="open = false"><span class="link-gold__line" aria-hidden="true"></span></x-cms-link>
        </div>
    </nav>
</div>
