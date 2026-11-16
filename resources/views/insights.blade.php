@extends('layouts.site')

@php
    $home = cms()->page('home');
    $topics = [
        [
            'number' => '01',
            'eyebrow' => 'Architecture',
            'title' => $home['landmark']['title'],
            'body' => $home['landmark']['lead'],
            'image' => $home['landmark']['image'],
            'alt' => $home['landmark']['image_alt'],
            'href' => route('home').'#landmark',
            'link' => 'Explore the architecture',
        ],
        [
            'number' => '02',
            'eyebrow' => 'Residences',
            'title' => $home['residences']['title'],
            'body' => $home['residences']['body'],
            'image' => $home['residences']['image'],
            'alt' => $home['residences']['image_alt'],
            'href' => route('home').'#residences',
            'link' => 'Explore the residences',
        ],
        [
            'number' => '03',
            'eyebrow' => 'The experience',
            'title' => $home['experience']['swim_title'],
            'body' => $home['experience']['swim_body'],
            'image' => $home['experience']['swim_image'],
            'alt' => $home['experience']['swim_alt'],
            'href' => route('home').'#experience',
            'link' => 'Explore the experience',
        ],
    ];
@endphp

@section('title', 'Insights | Santorini Residences, Westlands Nairobi')
@section('description', 'Explore the ideas behind Santorini Residences: distinctive architecture, considered homes and resort-inspired living in Westlands, Nairobi.')
@section('canonical', route('insights'))

@section('content')
    <section class="relative isolate flex min-h-[68svh] items-end overflow-hidden bg-navy-deep text-pearl" aria-labelledby="insights-title">
        <x-img src="media/tower-sunset.webp" alt="The curved Santorini Residences tower at sunset in Westlands, Nairobi." priority class="absolute inset-0 -z-10 h-full w-full object-cover object-center" />
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-navy-deep/85 via-navy-deep/40 to-navy-deep/10"></div>
        <div class="absolute inset-x-0 bottom-0 -z-10 h-2/3 bg-gradient-to-t from-navy-deep/80 to-transparent"></div>
        <div class="mx-auto w-full max-w-[1600px] px-5 pb-16 pt-36 md:px-10 md:pb-24">
            <p class="site-kicker text-pearl/70">Santorini Residences · Journal</p>
            <h1 id="insights-title" class="site-display mt-6 max-w-4xl text-6xl md:text-8xl">Insights</h1>
            <p class="mt-7 max-w-2xl font-serif text-2xl leading-snug text-pearl/90 md:text-3xl">Notes on architecture, considered homes and urban resort living in Westlands.</p>
        </div>
    </section>

    <section class="mx-auto max-w-[1600px] px-5 py-24 md:px-10 md:py-36" aria-labelledby="journal-title">
        <div class="grid gap-8 md:grid-cols-12 md:items-end">
            <div class="md:col-span-7">
                <p class="site-kicker text-navy">The journal</p>
                <h2 id="journal-title" class="site-display mt-5 max-w-3xl text-5xl text-ink md:text-6xl">A closer look at the ideas behind Santorini.</h2>
            </div>
            <p class="max-w-lg text-sm leading-relaxed text-stone md:col-span-4 md:col-start-9">We’re preparing stories about the details, choices and experiences that shape life at Santorini Residences. In the meantime, explore the themes that bring the project together.</p>
        </div>

        <div class="mt-16 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($topics as $topic)
                <article class="group min-w-0" data-reveal>
                    <a href="{{ $topic['href'] }}" class="block" aria-label="{{ $topic['link'] }}">
                        <div class="media-zoom relative aspect-[4/3] overflow-hidden bg-limestone">
                            <x-img :src="$topic['image']" :alt="$topic['alt']" sizes="(min-width: 1280px) 33vw, (min-width: 768px) 50vw, 100vw" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]" />
                        </div>
                        <div class="flex items-center justify-between border-b border-navy/20 py-5">
                            <p class="site-kicker text-stone">{{ $topic['eyebrow'] }}</p>
                            <span class="font-serif text-sm italic text-stone">{{ $topic['number'] }}</span>
                        </div>
                        <h3 class="mt-5 font-serif text-3xl leading-tight text-ink transition-colors group-hover:text-navy md:text-4xl">{{ $topic['title'] }}</h3>
                        <p class="mt-4 text-sm leading-relaxed text-ink/70">{{ $topic['body'] }}</p>
                        <span class="link-navy mt-7">{{ $topic['link'] }}</span>
                    </a>
                </article>
            @endforeach
        </div>

        <div class="mt-20 border-t border-limestone pt-8 text-center">
            <p class="font-serif text-2xl italic text-navy">New stories are taking shape.</p>
            <p class="mt-2 text-sm text-stone">For a closer introduction to Santorini, speak with our team.</p>
            <a href="{{ route('enquire') }}" class="site-button site-button-navy mt-7">Make an enquiry</a>
        </div>
    </section>
@endsection
