@extends('layouts.site')

@php
    $page = cms()->page('home');
    $hero = $page['hero'];
    $project = $page['project'];
    $landmark = $page['landmark'];
    $facade = $page['facade'];
    $distinctions = $page['distinctions'];
    $overview = $page['residences'];
    $experience = $page['experience'];
    $ownership = $page['ownership'];
    $location = $page['location'];
    $brand = $page['brand'];
    $intro = $page['introduction'];
@endphp

@section('title', $page['meta']['title'])
@section('description', $page['meta']['description'])

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ApartmentComplex',
    'name' => 'Santorini Residences',
    '@id' => \App\Support\Seo::url().'#residences',
    'hasMap' => config('location.map_url'),
    'description' => 'A landmark residential development on Lantana Road, Westlands, Nairobi. 328 residences, including 1, 2 and 3-bedroom homes and exclusive loft residences.',
    'url' => \App\Support\Seo::url(),
    'image' => cms_asset($hero['image']),
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'Lantana Road',
        'addressLocality' => 'Westlands',
        'addressRegion' => 'Nairobi',
        'addressCountry' => 'KE',
    ],
    'numberOfAccommodationUnits' => 328,
    'brand' => ['@type' => 'Brand', 'name' => 'LOVE HOMES', 'slogan' => 'Building Homes With Love. Creating a Better Life.'],
    'developer' => ['@type' => 'Organization', 'name' => 'Olmaa Lands Limited'],
    'parentOrganization' => [
        '@type' => 'Organization',
        'name' => 'Jiangsu Hetian Construction Co., Ltd.',
        'alternateName' => ['江苏禾田建设有限公司', 'Hetian Construction'],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    <section class="relative min-h-[100svh] bg-ink text-white">
        <div class="absolute inset-0 overflow-hidden">
            <x-img :src="$hero['image']" :alt="$hero['image_alt']" priority class="hero-still absolute inset-0 h-full w-full object-cover object-[center_42%]" />
            @if ($hero['video'])
                <video class="hero-film absolute inset-0 h-full w-full object-cover object-center" data-hero-film data-src="{{ cms_asset($hero['video']) }}" @if ($hero['video_mobile'] ?? '') data-src-mobile="{{ cms_asset($hero['video_mobile']) }}" @endif muted loop playsinline preload="none" aria-hidden="true"></video>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/25 to-ink/30"></div>
        </div>
        <div class="relative mx-auto flex min-h-[100svh] w-full min-w-0 max-w-[1600px] flex-col justify-end px-5 pb-12 pt-32 md:px-10 md:pb-16">
            <p class="site-kicker text-white/75">{{ $hero['kicker'] }}</p>
            <h1 class="site-display mt-6 max-w-5xl text-[18vw] text-white sm:text-8xl md:text-[8.5rem]">{{ $hero['title'] }}</h1>
            <p class="mt-6 max-w-[15.5rem] font-serif text-2xl leading-snug text-white/90 sm:max-w-xl sm:text-3xl">{{ $hero['tagline'] }}</p>
            <p class="mt-4 max-w-[15.5rem] text-sm leading-relaxed tracking-wide text-white/75 sm:max-w-lg sm:text-base">{{ $hero['subline'] }}</p>
            <div class="mt-10 flex flex-col items-start gap-5 sm:flex-row sm:items-center">
                <x-cms-link :link="$hero['primary']" class="site-button site-button-on-dark" />
                <x-cms-link :link="$hero['secondary']" class="link-line text-[0.68rem] tracking-[0.16em] uppercase sm:tracking-[0.2em]" />
            </div>
            @if ($hero['stats'])
                <dl class="mt-14 grid grid-cols-3 gap-6 border-t border-white/20 pt-6 text-white/80 md:max-w-2xl">
                    @foreach ($hero['stats'] as $stat)
                        <div>
                            <dt class="site-kicker text-[0.58rem] text-white/55">{{ $stat['label'] }}</dt>
                            <dd class="mt-2 font-serif text-3xl md:text-4xl">{{ $stat['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>
    </section>

    <section id="project" class="mx-auto max-w-[1600px] px-5 py-28 md:px-10 md:py-44" aria-labelledby="project-title">
        <div class="grid items-end gap-12 lg:grid-cols-12" data-reveal>
            <div class="lg:col-span-3 lg:pb-3">
                <span class="block font-serif text-[1.05rem] italic tracking-[0.04em] text-navy">01</span>
                <div class="mt-4 flex items-center gap-4">
                    @include('partials.wave-mark-navy')
                    <p class="site-kicker text-navy">{{ $project['kicker'] }}</p>
                </div>
            </div>
            <h2 id="project-title" class="site-display text-balance text-5xl text-ink sm:text-6xl lg:col-span-9 lg:text-[5.4rem]">{{ $project['title'] }} @if ($project['title_accent'])<span class="accent-navy">{{ $project['title_accent'] }}</span>@endif</h2>
        </div>

        <div class="mt-16 grid gap-10 lg:mt-20 lg:grid-cols-12 lg:gap-16" data-reveal>
            <p class="font-serif text-2xl leading-snug text-ink md:text-[1.75rem] lg:col-span-6">{{ $project['lead'] }}</p>
            <div class="lg:col-span-5 lg:col-start-8 lg:pt-2">
                <p class="text-base leading-[1.85] text-ink/72 md:text-[1.05rem]">{{ $project['body'] }}</p>
                <x-cms-link :link="$project['link']" class="link-navy mt-10" />
            </div>
        </div>

        @if ($project['facts'])
            <dl class="mt-24 grid grid-cols-2 gap-x-8 gap-y-14 border-t border-navy pt-12 md:mt-28 md:grid-cols-4 md:gap-x-10" data-reveal>
                @foreach ($project['facts'] as $fact)
                    <div @class(['md:border-r md:border-navy/12 md:pr-8' => ! $loop->last])>
                        <dt class="site-kicker text-stone">{{ $fact['label'] }}</dt>
                        <dd class="mt-5 font-serif text-5xl leading-none text-ink md:text-6xl">{{ $fact['value'] }}<span class="fact-unit">{{ $fact['unit'] }}</span></dd>
                    </div>
                @endforeach
            </dl>
        @endif
    </section>

    <section id="landmark" class="landmark relative isolate overflow-hidden bg-navy-deep text-pearl" aria-labelledby="landmark-title" data-inview>
        <div class="absolute inset-0 -z-10">
            <x-img :src="$landmark['image']" :alt="$landmark['image_alt']" class="landmark__still h-full w-full object-cover object-[34%_center] lg:object-[92%_center]" />
            <div class="absolute inset-0 bg-navy-deep/55 lg:hidden"></div>
            <div class="absolute inset-0 hidden bg-gradient-to-l from-navy-deep/90 via-navy-deep/40 to-navy-deep/0 lg:block"></div>
            <div class="absolute inset-x-0 bottom-0 h-2/5 bg-gradient-to-t from-navy-deep/90 to-transparent"></div>
        </div>
        <span class="landmark__frame" aria-hidden="true"></span>

        <div class="mx-auto flex min-h-[100svh] max-w-[1600px] flex-col px-5 pb-14 pt-28 md:px-10 md:pb-16 md:pt-36 lg:pt-40">
            <div class="grid lg:grid-cols-12 lg:items-start" data-reveal>
                <div class="lg:col-span-4">
                    <span class="block font-serif text-[1.05rem] italic tracking-[0.04em] text-champagne">02</span>
                    <div class="mt-4 flex items-center gap-4">
                        @include('partials.wave-mark')
                        <p class="site-kicker whitespace-nowrap text-pearl">{{ $landmark['kicker'] }}</p>
                    </div>
                </div>

                <h2 id="landmark-title" class="mt-10 font-serif text-3xl leading-snug text-pearl md:text-4xl lg:col-span-5 lg:col-start-8 lg:mt-0">{{ $landmark['title'] }}</h2>
            </div>

            <div class="mt-12 grid flex-1 items-end lg:mt-16 lg:grid-cols-12" data-reveal>
                <div class="border-l border-champagne/40 pl-6 md:pl-8 lg:col-span-4 lg:col-start-9">
                    <p class="font-serif text-2xl leading-snug text-pearl md:text-[1.65rem]">{{ $landmark['lead'] }}</p>
                    <p class="mt-6 text-[0.95rem] leading-[1.85] text-pearl/75">{{ $landmark['body'] }}</p>
                </div>
            </div>

            @if ($landmark['rail'])
                <dl class="landmark__rail mt-16 grid gap-8 border-t border-champagne/40 pt-8 sm:grid-cols-3 lg:mt-14" data-reveal>
                    @foreach ($landmark['rail'] as $item)
                        <div>
                            <dt class="site-kicker text-champagne">{{ $item['label'] }}</dt>
                            <dd class="mt-3 font-serif text-2xl text-pearl md:text-[1.65rem]">{{ $item['text'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>
    </section>

    <section id="facade" class="facade bg-ivory" aria-labelledby="facade-title" data-inview>
        <div class="grid lg:grid-cols-2">
            <figure class="relative isolate min-h-[64vh] overflow-hidden bg-navy-deep lg:min-h-[100vh]">
                <x-img :src="$facade['image']" :alt="$facade['image_alt']" sizes="(min-width: 1024px) 50vw, 100vw" class="facade__still absolute inset-0 h-full w-full object-cover" />
                <div class="absolute inset-x-0 bottom-0 h-2/5 bg-gradient-to-t from-navy-deep/80 via-navy-deep/30 to-transparent"></div>
                <span class="facade__frame" aria-hidden="true"></span>
                @if ($facade['caption'])
                    <figcaption class="absolute bottom-10 left-10 right-10 flex items-center gap-4 md:bottom-16 md:left-16">
                        @include('partials.wave-mark')
                        <span class="site-kicker text-pearl">{{ $facade['caption'] }}</span>
                    </figcaption>
                @endif
            </figure>

            <div class="flex flex-col justify-center px-5 py-24 md:px-14 md:py-32 lg:px-16 xl:px-24">
                <div data-reveal>
                    <span class="block font-serif text-[1.05rem] italic tracking-[0.04em] text-navy">03</span>
                    <div class="mt-4 flex items-center gap-4">
                        @include('partials.wave-mark-navy')
                        <p class="site-kicker text-navy">{{ $facade['kicker'] }}</p>
                    </div>
                    <h2 id="facade-title" class="site-display mt-10 text-balance text-5xl text-ink md:text-6xl">{{ $facade['title'] }} @if ($facade['title_accent'])<span class="accent-navy">{{ $facade['title_accent'] }}</span>@endif</h2>
                    <p class="mt-8 max-w-md text-[0.95rem] leading-[1.85] text-ink/72">{{ $facade['body'] }}</p>
                </div>

                @if ($facade['materials'])
                    <dl class="mt-14 border-t border-navy md:mt-16" data-reveal>
                        @foreach ($facade['materials'] as $material)
                            <div class="material grid gap-x-8 py-7 md:grid-cols-[1fr_auto] md:items-baseline md:py-8">
                                <dt class="font-serif text-3xl leading-none text-ink md:text-[2.1rem]"><span class="material__index font-serif" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $material['name'] }}</dt>
                                <dd class="site-kicker mt-4 pl-11 text-navy/70 md:col-start-2 md:row-start-1 md:mt-0 md:pl-0 md:text-right">{{ $material['spec'] }}</dd>
                                <dd class="mt-3 max-w-md pl-11 text-sm leading-relaxed text-ink/65 md:col-span-2">{{ $material['body'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>
        </div>
    </section>

    @php($features = \App\Support\SiteContent::features())
    @php($illustrated = array_values(array_filter($features, fn ($feature) => $feature['image'])))
    @php($essentials = array_values(array_filter($features, fn ($feature) => ! $feature['image'])))
    <section id="distinctions" class="mx-auto max-w-[1600px] px-5 py-28 md:px-10 md:py-44" aria-labelledby="distinctions-title">
        <div class="grid items-end gap-12 lg:grid-cols-12" data-reveal>
            <div class="lg:col-span-3 lg:pb-3">
                <span class="block font-serif text-[1.05rem] italic tracking-[0.04em] text-navy">04</span>
                <div class="mt-4 flex items-center gap-4">
                    @include('partials.wave-mark-navy')
                    <p class="site-kicker text-navy">{{ $distinctions['kicker'] }}</p>
                </div>
            </div>
            <h2 id="distinctions-title" class="site-display text-balance text-5xl text-ink sm:text-6xl lg:col-span-9 lg:text-[5.4rem]">{{ $distinctions['title'] }} @if ($distinctions['title_accent'])<span class="accent-navy">{{ $distinctions['title_accent'] }}</span>@endif</h2>
        </div>

        <div class="mt-24 md:mt-32">
            @foreach ($illustrated as $feature)
                @php($mediaLeft = $loop->even)
                <article class="distinction grid gap-x-10 gap-y-8 py-16 md:grid-cols-12 md:py-24" data-reveal>
                    <p class="distinction__index text-5xl md:col-span-2 md:text-6xl" aria-hidden="true">{{ $feature['n'] }}</p>
                    <div class="md:col-span-4">
                        <h3 class="font-serif text-4xl leading-[1.02] text-ink md:text-5xl">{{ $feature['title'] }}</h3>
                        <p class="mt-5 font-serif text-xl italic leading-snug text-navy/80">{{ $feature['line'] }}</p>
                    </div>
                    <p class="text-[0.95rem] leading-[1.85] text-ink/72 md:col-span-5 md:col-start-8 md:text-base">{{ $feature['body'] }}</p>
                    <figure class="distinction__media {{ $mediaLeft ? 'distinction__media--left md:col-start-1' : 'md:col-start-3' }} mt-6 md:col-span-10 md:mt-10">
                        <x-img :src="$feature['image']" :alt="$feature['alt']" sizes="(min-width: 768px) 84vw, 100vw" class="aspect-[4/3] w-full object-cover sm:aspect-[16/9] md:aspect-[2/1]" />
                    </figure>
                </article>
            @endforeach
        </div>

        @if ($essentials)
            <div class="distinction-trio grid gap-10 border-t border-navy pt-14 md:grid-cols-3 md:pt-16" data-reveal>
                @foreach ($essentials as $feature)
                    <article>
                        <p class="distinction__index text-4xl md:text-5xl" aria-hidden="true">{{ $feature['n'] }}</p>
                        <h3 class="mt-8 font-serif text-3xl leading-[1.05] text-ink md:text-[2.1rem]">{{ $feature['title'] }}</h3>
                        <p class="mt-4 font-serif text-lg italic leading-snug text-navy/80">{{ $feature['line'] }}</p>
                        <p class="mt-6 text-[0.95rem] leading-[1.85] text-ink/72">{{ $feature['body'] }}</p>
                    </article>
                @endforeach
            </div>
        @endif

        <div class="mt-28 grid gap-10 border-t border-navy pt-14 md:mt-36 md:pt-16 lg:grid-cols-12" data-reveal>
            <div class="flex items-center gap-4 self-start lg:col-span-3 lg:flex-col lg:items-start lg:gap-5 lg:pt-3">
                @include('partials.wave-mark-navy')
                <p class="site-kicker whitespace-nowrap text-navy">{{ $distinctions['difference_kicker'] }}</p>
            </div>
            <div class="lg:col-span-9">
                <p class="max-w-3xl font-serif text-3xl leading-snug md:text-4xl">{{ $distinctions['difference_lead'] }}</p>
                <p class="mt-8 max-w-xl text-[0.95rem] leading-[1.85] text-ink/72">{{ $distinctions['difference_body'] }}</p>
            </div>
        </div>
    </section>

    <section id="residences" class="bg-ink text-paper">
        <div class="mx-auto grid max-w-[1600px] gap-16 px-5 py-24 md:px-10 md:py-32 lg:grid-cols-12">
            <div class="lg:col-span-5" data-reveal>
                <p class="site-kicker text-sand">{{ $overview['kicker'] }}</p>
                <h2 class="site-display mt-6 text-5xl md:text-6xl">{{ $overview['title'] }}</h2>
                <p class="mt-6 max-w-md text-base leading-relaxed text-sand">{{ $overview['body'] }}</p>
                <x-cms-link :link="$overview['link']" class="site-button site-button-on-dark mt-10" />
            </div>
            <div class="lg:col-span-6 lg:col-start-7">
                @foreach (\App\Support\SiteContent::residences() as $home)
                    <article class="border-t border-white/15 py-8">
                        <div class="flex flex-wrap items-baseline justify-between gap-3">
                            <h3 class="font-serif text-3xl md:text-4xl">{{ $home['name'] }}</h3>
                            <p class="text-sm text-sand">{{ $home['meta'] }}</p>
                        </div>
                        <p class="mt-4 max-w-xl text-sm leading-relaxed text-sand">{{ $home['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
        @if ($overview['image'])
            <figure class="media-zoom">
                <x-img :src="$overview['image']" :alt="$overview['image_alt']" class="max-h-[80vh] w-full object-cover" />
            </figure>
        @endif
    </section>

    <section id="experience">
        <div class="grid lg:grid-cols-12">
            <figure class="media-zoom relative min-h-[78vh] lg:col-span-7">
                <x-img :src="$experience['swim_image']" :alt="$experience['swim_alt']" sizes="(min-width: 1024px) 58vw, 100vw" class="absolute inset-0 h-full w-full object-cover" />
            </figure>
            <div class="flex flex-col justify-center bg-paper px-5 py-16 md:px-12 lg:col-span-5 lg:py-20" data-reveal>
                <p class="site-kicker text-stone">{{ $experience['swim_kicker'] }}</p>
                <h2 class="site-display mt-5 text-5xl md:text-6xl">{{ $experience['swim_title'] }}</h2>
                <p class="mt-6 max-w-md leading-relaxed text-ink/80">{{ $experience['swim_body'] }}</p>
                <x-cms-link :link="$experience['swim_link']" class="link-line mt-12 w-fit text-[0.7rem] tracking-[0.18em] uppercase" />
            </div>
        </div>

        <div class="grid md:grid-cols-2">
            @foreach (['dine', 'relax'] as $moment)
                <figure class="media-zoom relative min-h-[70vh]">
                    <x-img :src="$experience[$moment.'_image']" :alt="$experience[$moment.'_alt']" sizes="(min-width: 768px) 50vw, 100vw" class="absolute inset-0 h-full w-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div>
                    <figcaption class="absolute bottom-0 left-0 p-6 text-white md:p-10">
                        <p class="site-kicker text-white/70">{{ $experience[$moment.'_kicker'] }}</p>
                        <p class="mt-3 font-serif text-4xl">{{ $experience[$moment.'_title'] }}</p>
                    </figcaption>
                </figure>
            @endforeach
        </div>

        @if ($experience['amenities'])
            <div class="mx-auto grid max-w-[1600px] gap-10 px-5 py-20 md:grid-cols-3 md:px-10 md:py-28">
                @foreach ($experience['amenities'] as $amenity)
                    <div class="border-t border-limestone pt-6">
                        <p class="site-kicker text-stone">{{ $amenity['kicker'] }}</p>
                        <h3 class="mt-4 font-serif text-3xl">{{ $amenity['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-stone">{{ $amenity['body'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section id="ownership" class="aegean relative overflow-hidden text-pearl" aria-labelledby="ownership-title">
        <div class="mx-auto grid max-w-[1600px] items-start gap-16 px-5 py-28 md:px-10 md:py-40 lg:grid-cols-12">
            <div class="lg:sticky lg:top-32 lg:col-span-5" data-reveal>
                <div class="flex items-center gap-4">
                    @include('partials.wave-mark')
                    <p class="site-kicker text-pearl">{{ $ownership['kicker'] }}</p>
                </div>
                <h2 id="ownership-title" class="site-display mt-10 text-5xl text-pearl md:text-6xl">{{ $ownership['title'] }} @if ($ownership['title_accent'])<span class="display-accent">{{ $ownership['title_accent'] }}</span>@endif</h2>
                <p class="mt-8 max-w-md text-[0.95rem] leading-[1.85] text-silver/75">{{ $ownership['body'] }}</p>
                <div class="mt-12 flex flex-wrap items-center gap-x-8 gap-y-5">
                    <x-cms-link :link="$ownership['primary']" class="site-button site-button-on-dark" />
                    <x-cms-link :link="$ownership['secondary']" class="link-gold"><span class="link-gold__line" aria-hidden="true"></span></x-cms-link>
                </div>
            </div>
            <ul class="lg:col-span-6 lg:col-start-7" data-reveal>
                @foreach ($ownership['points'] as $point)
                    <li class="merit grid grid-cols-[2.75rem_1fr] py-7 md:py-8">
                        <span class="merit__index pt-1.5" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <p class="font-serif text-[1.65rem] leading-tight text-pearl md:text-[1.85rem]">{{ $point['title'] }}</p>
                            <p class="mt-2.5 max-w-md text-sm leading-relaxed text-silver/65">{{ $point['body'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section id="location" class="mx-auto grid max-w-[1600px] items-center gap-16 px-5 py-28 md:px-10 md:py-44 lg:grid-cols-12" aria-labelledby="location-title">
        <div class="lg:col-span-5" data-reveal>
            <div class="flex items-center gap-4">
                @include('partials.wave-mark-navy')
                <p class="site-kicker text-navy">{{ $location['kicker'] }}</p>
            </div>
            <h2 id="location-title" class="site-display mt-10 text-5xl text-ink md:text-6xl">{{ $location['title'] }} @if ($location['title_accent'])<span class="accent-navy">{{ $location['title_accent'] }}</span>@endif</h2>
            <p class="mt-8 max-w-md text-[0.95rem] leading-[1.85] text-ink/72">{{ $location['body'] }}</p>

            @if ($location['destinations'])
                <p class="site-kicker mt-14 text-stone">{{ $location['access_label'] }}</p>
                <ul class="mt-5 grid grid-cols-2 gap-x-8 border-t border-navy">
                    @foreach ($location['destinations'] as $destination)
                        <li class="border-b border-navy/12 py-4 font-serif text-xl leading-tight text-ink md:text-[1.4rem]">{{ $destination }}</li>
                    @endforeach
                </ul>
            @endif

            <x-cms-link :link="$location['visit']" class="link-navy mt-12" />
        </div>

        <div class="lg:col-span-6 lg:col-start-7" data-reveal>
            <figure>
                <div class="map-frame">
                    <div class="map-frame__media">
                        <iframe title="Map showing {{ $location['place_name'] }}, {{ $location['place_address'] }}" class="h-[440px] w-full md:h-[560px]" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin" src="{{ config('location.embed_url') }}"></iframe>
                        <span class="map-frame__tint" aria-hidden="true"></span>
                    </div>
                </div>
                <figcaption class="mt-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="font-serif text-2xl leading-tight text-ink">{{ $location['place_name'] }}</p>
                        <p class="mt-2 text-sm leading-relaxed text-ink/65">{{ $location['place_address'] }}</p>
                    </div>
                    <a href="{{ config('location.map_url') }}" class="link-navy shrink-0" target="_blank" rel="noopener noreferrer">{{ $location['map_link_label'] }}</a>
                </figcaption>
            </figure>
        </div>
    </section>

    <section class="border-t border-limestone">
        <div class="mx-auto grid max-w-[1600px] items-center gap-12 px-5 py-24 md:px-10 lg:grid-cols-12">
            <x-img :src="$brand['logo']" :alt="$brand['logo_alt']" sizes="12rem" :fallback="400" class="w-48 lg:col-span-4" />
            <div class="lg:col-span-7 lg:col-start-6" data-reveal>
                <p class="site-kicker text-stone">{{ $brand['kicker'] }}</p>
                <h2 class="site-display mt-6 text-5xl md:text-6xl">{{ $brand['title'] }}</h2>
                <p class="mt-6 max-w-xl leading-relaxed text-ink/80">{{ $brand['body'] }}</p>
                <x-cms-link :link="$brand['link']" class="link-line mt-8 inline-block text-[0.7rem] tracking-[0.18em] uppercase" />
            </div>
        </div>
    </section>

    <section id="introduction" class="closing relative isolate overflow-hidden bg-navy-deep text-pearl" aria-labelledby="introduction-title" data-inview>
        <div class="absolute inset-0 -z-10">
            <x-img :src="$intro['image']" :alt="$intro['image_alt']" class="closing__still h-full w-full object-cover object-[62%_center]" />
            <div class="absolute inset-0 bg-gradient-to-r from-navy-deep/90 via-navy-deep/50 to-navy-deep/5"></div>
            <div class="absolute inset-x-0 bottom-0 h-3/5 bg-gradient-to-t from-navy-deep/95 via-navy-deep/55 to-transparent"></div>
        </div>
        <span class="landmark__frame" aria-hidden="true"></span>

        <div class="mx-auto flex min-h-[92svh] max-w-[1600px] flex-col justify-end px-5 pb-14 pt-36 md:px-10 md:pb-16">
            <div class="max-w-3xl" data-reveal>
                <div class="flex items-center gap-4">
                    @include('partials.wave-mark')
                    <p class="site-kicker text-pearl">{{ $intro['kicker'] }}</p>
                </div>
                <h2 id="introduction-title" class="site-display mt-10 text-5xl text-pearl sm:text-6xl lg:text-[5.4rem]">{{ $intro['title'] }} @if ($intro['title_accent'])<span class="display-accent">{{ $intro['title_accent'] }}</span>@endif</h2>
                <p class="mt-8 max-w-lg text-[1.05rem] leading-[1.85] text-silver/80">{{ $intro['body'] }}</p>
                <div class="mt-12 flex flex-wrap items-center gap-x-8 gap-y-5">
                    <x-cms-link :link="$intro['primary']" class="site-button site-button-on-dark" />
                    <x-cms-link :link="$intro['secondary']" class="link-gold"><span class="link-gold__line" aria-hidden="true"></span></x-cms-link>
                </div>
            </div>

            @if ($intro['rail'])
                <ul class="landmark__rail mt-20 grid gap-8 border-t border-champagne/40 pt-8 sm:grid-cols-3 md:mt-24" data-reveal>
                    @foreach ($intro['rail'] as $item)
                        <li>
                            <a href="{{ cms_href($item['url']) }}" class="closing__link flex items-end justify-between gap-6">
                                <span>
                                    <span class="site-kicker block text-champagne">{{ $item['label'] }}</span>
                                    <span class="mt-3 block font-serif text-2xl leading-snug text-pearl md:text-[1.65rem]">{{ $item['action'] }}</span>
                                </span>
                                <svg class="closing__arrow mb-2.5 h-3 w-7 shrink-0 text-champagne" viewBox="0 0 28 12" fill="none" aria-hidden="true">
                                    <path d="M0 6h26M21 1l5 5-5 5" stroke="currentColor" stroke-width="1"/>
                                </svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
@endsection
