@extends('layouts.site')

@php
    $page = cms()->page('residences');
    $hero = $page['hero'];
    $interiors = $page['interiors'];
    $closing = $page['closing'];
    $visitLabel = $page['types']['visit_label'];
@endphp

@section('title', $page['meta']['title'])
@section('description', $page['meta']['description'])

@section('content')
    <section class="relative min-h-[78vh] bg-ink text-white">
        <x-img :src="$hero['image']" :alt="$hero['image_alt']" priority class="absolute inset-0 h-full w-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-b from-ink/70 via-ink/20 to-ink/75"></div>
        <div class="relative mx-auto flex min-h-[78vh] max-w-[1600px] flex-col justify-end px-5 pb-16 pt-32 md:px-10">
            <p class="site-kicker text-white/70">{{ $hero['kicker'] }}</p>
            <h1 class="site-display mt-5 max-w-4xl text-6xl text-white md:text-8xl">{{ $hero['title'] }}</h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/80">{{ $hero['body'] }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-[1100px] px-5 py-28 md:px-10 md:py-40" aria-label="Residence types">
        @foreach (\App\Support\SiteContent::residences() as $home)
            <article class="distinction grid gap-x-10 gap-y-8 py-16 md:grid-cols-12 md:py-24" data-reveal>
                <p class="distinction__index text-5xl md:col-span-2 md:text-6xl" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                <div class="md:col-span-5">
                    <h2 class="font-serif text-5xl leading-none text-ink md:text-[3.4rem]">{{ $home['name'] }}</h2>
                    @if ($home['specs'])
                        <ul class="mt-7 space-y-2.5">
                            @foreach ($home['specs'] as $spec)
                                <li class="flex items-center gap-3 text-sm tracking-[0.04em] text-ink/70">
                                    <span class="h-px w-4 shrink-0 bg-champagne" aria-hidden="true"></span>
                                    {{ $spec }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="md:col-span-5">
                    <p class="text-[1.05rem] leading-[1.85] text-ink/75">{{ $home['body'] }}</p>
                    @if (! empty($home['options']))
                        <ul class="mt-8 grid gap-4 sm:grid-cols-2">
                            @foreach ($home['options'] as $option)
                                @php([$optionName, $optionBody] = array_pad(explode(': ', $option, 2), 2, ''))
                                <li class="border-t border-navy pt-4">
                                    <p class="font-serif text-xl italic leading-snug text-navy">{{ $optionName }}</p>
                                    @if ($optionBody)
                                        <p class="mt-2 text-sm leading-relaxed text-ink/65">{{ ucfirst($optionBody) }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="mt-10 flex flex-wrap items-center gap-x-10 gap-y-5">
                        <x-cms-link :link="['label' => $home['link_label'], 'url' => $home['url']]" class="link-navy" />
                        @if ($visitLabel)
                            <a href="{{ route('visit.book', ['residence' => \Illuminate\Support\Str::after($home['url'] ?? '', 'interest=') ?: null]) }}" class="link-gold link-gold--navy">
                                <span class="link-gold__line" aria-hidden="true"></span>
                                {{ $visitLabel }}
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    @php($studyLayout = [
        ['md:col-span-8', 'md:aspect-[16/10]', 'study__media--sail'],
        ['md:col-span-4 md:mt-40', 'md:aspect-[4/5]', 'study__media--sail-right'],
        ['md:col-span-5 md:col-start-2', 'md:aspect-[4/5]', 'study__media--sail'],
        ['md:col-span-6 md:col-start-7 md:mt-48', 'md:aspect-[4/3]', 'study__media--sail-right'],
        ['md:col-span-4', 'md:aspect-[3/4]', 'study__media--sail'],
        ['md:col-span-4 md:mt-24', 'md:aspect-[3/4]', ''],
        ['md:col-span-4 md:mt-48', 'md:aspect-[3/4]', 'study__media--sail-right'],
        ['md:col-span-7', 'md:aspect-[16/10]', 'study__media--sail'],
        ['md:col-span-4 md:col-start-9 md:mt-32', 'md:aspect-[4/5]', 'study__media--sail-right'],
        ['md:col-span-10 md:col-start-2', 'md:aspect-[21/9]', 'study__media--sail'],
    ])
    <section id="interiors" class="border-t border-limestone bg-ivory" aria-labelledby="interiors-title">
        <div class="mx-auto max-w-[1600px] px-5 py-28 md:px-10 md:py-40">
            <div class="grid items-end gap-10 lg:grid-cols-12" data-reveal>
                <div class="lg:col-span-7">
                    <div class="flex items-center gap-4">
                        @include('partials.wave-mark-navy')
                        <p class="site-kicker text-navy">{{ $interiors['kicker'] }}</p>
                    </div>
                    <h2 id="interiors-title" class="site-display mt-10 text-5xl text-ink md:text-6xl lg:text-7xl">{{ $interiors['title'] }} @if ($interiors['title_accent'])<span class="accent-navy">{{ $interiors['title_accent'] }}</span>@endif</h2>
                </div>
                <p class="max-w-md border-l border-champagne/60 pl-6 text-[0.95rem] leading-[1.85] text-ink/72 lg:col-span-4 lg:col-start-9 lg:mb-3">{{ $interiors['body'] }}</p>
            </div>

            <div class="mt-20 grid items-start gap-x-10 gap-y-16 md:mt-28 md:grid-cols-12 md:gap-y-24">
                @foreach (\App\Support\SiteContent::rooms() as $room)
                    @php([$placement, $aspect, $corner] = $studyLayout[$loop->index % count($studyLayout)])
                    <figure class="study {{ $placement }}" data-reveal>
                        <div class="study__media {{ $corner }}">
                            <x-img :src="$room['image']" :alt="$room['alt']" :sizes="'(min-width: 768px) '.round((preg_match('/md:col-span-(\d+)/', $placement, $span) ? (int) $span[1] : 12) / 12 * 100).'vw, 100vw'" class="aspect-[4/3] h-full w-full object-cover {{ $aspect }}" />
                        </div>
                        <figcaption class="mt-5 flex items-center gap-5">
                            <span class="font-serif text-[1.05rem] italic tracking-[0.04em] text-navy/55" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="font-serif text-2xl leading-none text-ink md:text-[1.65rem]">{{ $room['title'] }}</span>
                            <span class="study__rule" aria-hidden="true"></span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto flex max-w-[1600px] flex-col items-start gap-8 px-5 py-24 md:flex-row md:items-end md:justify-between md:px-10">
        <h2 class="site-display max-w-xl text-5xl">{{ $closing['title'] }}</h2>
        <div class="flex flex-wrap items-center gap-x-10 gap-y-5">
            <x-cms-link :link="$closing['visit']" class="link-navy" />
            <x-cms-link :link="$closing['primary']" class="site-button" />
        </div>
    </section>
@endsection
