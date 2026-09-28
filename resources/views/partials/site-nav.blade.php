@php($navigation = cms('settings.navigation'))
<header class="fixed inset-x-0 top-0 z-50 transition-colors duration-500" :class="solid || open ? 'bg-paper/95 text-ink backdrop-blur-md' : 'bg-transparent text-white'">
    <div class="mx-auto flex h-20 max-w-[1600px] items-center justify-between px-5 md:h-24 md:px-10">
        <a href="{{ route('home') }}" class="group leading-none" aria-label="Santorini Residences, home">
            <img
                src="{{ \App\Support\ResponsiveImage::url(($navLocked ?? false) ? 'media/logo-santorini-ink.png' : 'media/logo-santorini.png', 400) }}"
                :src="(solid || open) ? '{{ \App\Support\ResponsiveImage::url('media/logo-santorini-ink.png', 400) }}' : '{{ \App\Support\ResponsiveImage::url('media/logo-santorini.png', 400) }}'"
                width="994" height="586"
                alt=""
                class="brand-mark"
            >
        </a>

        <nav class="hidden items-center gap-8 text-[0.68rem] tracking-[0.18em] uppercase lg:flex" aria-label="Primary">
            <a href="{{ route('home') }}#landmark" class="link-line">The Landmark</a>
            <a href="{{ route('residences') }}" class="link-line {{ request()->routeIs('residences') ? 'opacity-100' : '' }}">Residences</a>
            <a href="{{ route('home') }}#experience" class="link-line">Experience</a>
            <a href="{{ route('gallery') }}" class="link-line">Gallery</a>
            <a href="{{ route('about') }}" class="link-line">The House</a>
        </nav>

        <div class="flex items-center gap-6">
            <x-cms-link :link="$navigation['visit']" class="link-line hidden text-[0.68rem] tracking-[0.18em] uppercase xl:inline-block" />
            <x-cms-link :link="$navigation['cta']" class="site-button hidden md:inline-flex" x-bind:class="solid || open ? '' : 'site-button-on-dark'" />
            <button type="button" class="menu-toggle h-11 w-11 items-center justify-center border border-current" :class="solid || open ? 'bg-transparent' : 'bg-ink/55 text-white'" @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobile-menu" aria-label="Menu">
                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 17h16" stroke="currentColor" stroke-width="1.2"/></svg>
                <svg x-show="open" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.2"/></svg>
            </button>
        </div>
    </div>
</header>

<div id="mobile-menu" x-show="open" x-cloak x-transition.opacity.duration.400ms class="fixed inset-0 z-40 bg-paper text-ink lg:hidden" @keydown.escape.window="open = false">
    <nav class="flex h-full flex-col justify-end gap-4 overflow-y-auto px-6 pb-12 pt-28" aria-label="Mobile">
        <a href="{{ route('home') }}#landmark" class="font-serif text-4xl sm:text-5xl" @click="open = false">The Landmark</a>
        <a href="{{ route('residences') }}" class="font-serif text-4xl sm:text-5xl" @click="open = false">Residences</a>
        <a href="{{ route('home') }}#experience" class="font-serif text-4xl sm:text-5xl" @click="open = false">Experience</a>
        <a href="{{ route('gallery') }}" class="font-serif text-4xl sm:text-5xl" @click="open = false">Gallery</a>
        <a href="{{ route('about') }}" class="font-serif text-4xl sm:text-5xl" @click="open = false">The House</a>
        <a href="{{ route('home') }}#location" class="font-serif text-4xl sm:text-5xl" @click="open = false">Location</a>
        <div class="mt-6 flex flex-wrap items-center gap-x-8 gap-y-5">
            <x-cms-link :link="$navigation['cta']" class="site-button w-fit" x-on:click="open = false" />
            <x-cms-link :link="$navigation['visit']" class="link-gold link-gold--navy" x-on:click="open = false"><span class="link-gold__line" aria-hidden="true"></span></x-cms-link>
        </div>
    </nav>
</div>
