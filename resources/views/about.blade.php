@extends('layouts.site')

@php
    $page = cms()->page('about');
    $hero = $page['hero'];
    $brand = $page['brand'];
    $background = $page['background'];
    $why = $page['why'];
@endphp

@section('title', $page['meta']['title'])
@section('description', $page['meta']['description'])

@section('content')
    <section class="relative isolate overflow-hidden bg-navy-deep text-pearl" aria-labelledby="about-title" data-inview>
        <div class="absolute inset-0 -z-10">
            <x-img :src="$hero['image']" :alt="$hero['image_alt']" priority class="settle h-full w-full object-cover object-[30%_center] lg:object-[8%_center]" />
            <div class="absolute inset-0 bg-gradient-to-r from-navy-deep/80 via-navy-deep/30 to-navy-deep/0"></div>
            <div class="absolute inset-x-0 bottom-0 h-3/5 bg-gradient-to-t from-navy-deep/95 via-navy-deep/45 to-transparent"></div>
            <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-navy-deep/60 to-transparent"></div>
        </div>
        <span class="landmark__frame" aria-hidden="true"></span>

        <div class="mx-auto grid min-h-[88svh] max-w-[1600px] items-end gap-12 px-5 pb-16 pt-40 md:px-10 md:pb-24 lg:grid-cols-12">
            <div class="lg:col-span-8" data-reveal>
                <div class="flex items-center gap-4">
                    @include('partials.wave-mark')
                    <p class="site-kicker text-pearl">{{ $hero['kicker'] }}</p>
                </div>
                <h1 id="about-title" class="site-display mt-10 text-6xl text-pearl md:text-8xl">{{ $hero['title'] }}</h1>
                <p class="mt-8 max-w-xl font-serif text-3xl leading-snug text-pearl md:text-4xl">{{ $hero['tagline'] }} @if ($hero['tagline_accent'])<span class="display-accent">{{ $hero['tagline_accent'] }}</span>@endif</p>
            </div>
            @if ($hero['pledges'])
                <ul class="pledge max-w-sm border-l border-champagne/40 pl-6 md:pl-8 lg:col-span-4 lg:justify-self-end" data-reveal>
                    @foreach ($hero['pledges'] as $pledge)
                        <li class="py-3.5 font-serif text-xl italic text-pearl/90 first:pt-0 last:pb-0 md:text-2xl">{{ $pledge }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <section class="mx-auto grid max-w-[1600px] items-center gap-16 px-5 py-28 md:px-10 md:py-44 lg:grid-cols-12" aria-labelledby="brand-title">
        <div class="lg:col-span-4" data-reveal>
            <figure class="crest ml-4 max-w-[22rem] md:ml-5">
                <div class="crest__card px-10 py-14 md:px-14 md:py-16">
                    <x-img :src="$brand['logo']" :alt="$brand['logo_alt']" sizes="15rem" :fallback="400" class="mx-auto w-full max-w-[15rem]" />
                </div>
            </figure>
        </div>
        <div class="lg:col-span-7 lg:col-start-6" data-reveal>
            <div class="flex items-center gap-4">
                @include('partials.wave-mark-navy')
                <p class="site-kicker text-navy">{{ $brand['kicker'] }}</p>
            </div>
            <h2 id="brand-title" class="mt-10 max-w-2xl font-serif text-3xl leading-snug text-ink md:text-[2.5rem] md:leading-[1.25]">{{ $brand['title'] }} @if ($brand['title_accent'])<span class="accent-navy">{{ $brand['title_accent'] }}</span>@endif{{ $brand['title_after'] }}</h2>
            <p class="mt-10 max-w-xl text-[0.95rem] leading-[1.85] text-ink/72">{{ $brand['body'] }}</p>
        </div>
    </section>

    <section class="bg-ivory" aria-labelledby="background-title">
        <div class="mx-auto grid max-w-[1600px] items-start gap-16 px-5 py-28 md:px-10 md:py-40 lg:grid-cols-12">
            <div class="lg:sticky lg:top-32 lg:col-span-5" data-reveal>
                <div class="flex items-center gap-4">
                    @include('partials.wave-mark-navy')
                    <p class="site-kicker text-navy">{{ $background['kicker'] }}</p>
                </div>
                <h2 id="background-title" class="site-display mt-10 text-5xl text-ink md:text-6xl">{{ $background['title'] }} @if ($background['title_accent'])<span class="accent-navy">{{ $background['title_accent'] }}</span>@endif</h2>
                @if ($background['local_name'])
                    <p class="mt-5 font-serif text-2xl text-navy/60">{{ $background['local_name'] }}</p>
                @endif
                <p class="mt-10 text-[0.95rem] leading-[1.85] text-ink/72">{{ $background['body'] }}</p>
                @if ($background['body_2'])
                    <p class="mt-6 text-[0.95rem] leading-[1.85] text-ink/72">{{ $background['body_2'] }}</p>
                @endif

                @if ($background['facts'])
                    <dl class="mt-12 grid grid-cols-2 gap-x-8 gap-y-10 border-t border-navy pt-10">
                        @foreach ($background['facts'] as $fact)
                            <div>
                                <dt class="site-kicker text-stone">{{ $fact['label'] }}</dt>
                                <dd class="mt-3 font-serif text-4xl leading-none text-ink md:text-5xl">{{ $fact['value'] }}<span class="fact-unit">{{ $fact['unit'] }}</span></dd>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>

            <div class="lg:col-span-6 lg:col-start-7" data-reveal>
                <p class="site-kicker text-navy">{{ $background['work_kicker'] }}</p>
                <ul class="mt-8 border-t border-navy">
                    @foreach (\App\Support\SiteContent::portfolio() as $project)
                        <li class="material grid grid-cols-[2.75rem_1fr] items-baseline gap-y-2 py-6 sm:grid-cols-[2.75rem_1fr_auto] sm:gap-x-6">
                            <span class="material__index font-serif" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="font-serif text-2xl leading-snug text-ink md:text-[1.65rem]">{{ $project['name'] }}</span>
                            <span class="col-start-2 text-sm tracking-[0.04em] text-ink/60 sm:col-start-3 sm:text-right">{{ $project['area'] }}</span>
                        </li>
                    @endforeach
                </ul>
                @if ($background['note'])
                    <p class="mt-10 max-w-lg border-l border-champagne/60 pl-6 text-sm leading-[1.85] text-ink/65">{{ $background['note'] }}</p>
                @endif
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-[1600px] px-5 py-28 md:px-10 md:py-44" aria-labelledby="why-title">
        <div data-reveal>
            <div class="flex items-center gap-4">
                @include('partials.wave-mark-navy')
                <p class="site-kicker text-navy">{{ $why['kicker'] }}</p>
            </div>
            <h2 id="why-title" class="site-display mt-10 max-w-4xl text-5xl text-ink md:text-6xl lg:text-7xl">{{ $why['title'] }} @if ($why['title_accent'])<span class="accent-navy">{{ $why['title_accent'] }}</span>@endif</h2>
        </div>
        @if ($why['points'])
            <div class="mt-20 grid gap-x-10 gap-y-14 md:grid-cols-2 lg:grid-cols-4" data-reveal>
                @foreach ($why['points'] as $point)
                    <div class="border-t border-navy pt-8">
                        <span class="material__index block font-serif" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <p class="mt-6 font-serif text-3xl leading-snug text-ink">{{ $point }}</p>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="mt-20 flex flex-wrap items-center gap-x-10 gap-y-6" data-reveal>
            <x-cms-link :link="$why['primary']" class="site-button" />
            <x-cms-link :link="$why['visit']" class="link-gold link-gold--navy"><span class="link-gold__line" aria-hidden="true"></span></x-cms-link>
            <x-cms-link :link="$why['secondary']" class="link-navy" />
        </div>
    </section>
@endsection
