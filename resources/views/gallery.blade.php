@extends('layouts.site')

@php
    $page = cms()->page('gallery');
    $intro = $page['intro'];
    $closing = $page['closing'];
    $chapters = \App\Support\SiteContent::galleryChapters();
    $slides = [];
    foreach ($chapters as $chapter) {
        foreach ($chapter['items'] as $image) {
            $slides[] = [
                'src' => \App\Support\ResponsiveImage::url($image['image'], 1600),
                'srcset' => \App\Support\ResponsiveImage::srcset($image['image']),
                'thumb' => \App\Support\ResponsiveImage::url($image['image'], 400),
                'title' => $image['title'] ?? '',
                'alt' => $image['alt'] ?? '',
                'chapter' => $chapter['title'] ?: (\App\Support\ContentSchema::GALLERY_CHAPTERS[$chapter['key']] ?? ''),
            ];
        }
    }
    $frame = 0;
@endphp

@section('title', $page['meta']['title'])
@section('description', $page['meta']['description'])

@section('content')
    <div x-data="gallery(@js($slides))" @keydown.window="onKey($event)">
        <section class="mx-auto max-w-[1600px] px-5 pb-16 pt-32 md:px-10 md:pt-40">
            <p class="site-kicker text-stone">{{ $intro['kicker'] }}</p>
            <h1 class="site-display mt-6 max-w-4xl text-6xl md:text-8xl">{{ $intro['title'] }}</h1>
            <div class="mt-10 grid gap-8 md:grid-cols-12 md:items-end">
                <p class="max-w-xl text-lg leading-relaxed text-stone md:col-span-6">{{ $intro['body'] }}</p>
                <nav class="gallery-index md:col-span-6 md:justify-self-end" aria-label="Gallery chapters">
                    @foreach ($chapters as $chapter)
                        <a href="#chapter-{{ $chapter['key'] }}" class="gallery-index__link">
                            <span class="gallery-index__num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            {{ $chapter['title'] ?: \App\Support\ContentSchema::GALLERY_CHAPTERS[$chapter['key']] }}
                            <span class="gallery-index__count">{{ count($chapter['items']) }}</span>
                        </a>
                    @endforeach
                    <button type="button" class="gallery-index__view" @click="open(0)">
                        View all {{ count($slides) }}
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h15m0 0-5-5m5 5-5 5" stroke="currentColor" stroke-width="1.2"/></svg>
                    </button>
                </nav>
            </div>
        </section>

        @foreach ($chapters as $chapter)
            <section id="chapter-{{ $chapter['key'] }}" class="mx-auto max-w-[1600px] scroll-mt-24 px-3 pb-20 md:px-6 md:pb-28" aria-labelledby="chapter-{{ $chapter['key'] }}-title">
                <header class="gallery-chapter" data-reveal>
                    <span class="gallery-chapter__num" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h2 id="chapter-{{ $chapter['key'] }}-title" class="gallery-chapter__title {{ $chapter['title'] ? '' : 'sr-only' }}">{{ $chapter['title'] ?: \App\Support\ContentSchema::GALLERY_CHAPTERS[$chapter['key']] }}</h2>
                    <span class="gallery-chapter__rule" aria-hidden="true"></span>
                    <span class="gallery-chapter__count">{{ count($chapter['items']) }} {{ \Illuminate\Support\Str::plural('image', count($chapter['items'])) }}</span>
                </header>

                <div class="grid gap-3 md:grid-cols-12 md:gap-4">
                    @foreach ($chapter['items'] as $image)
                        <button type="button" class="gallery-frame media-zoom {{ $image['class'] }}" @click="open({{ $frame }}, $el)" aria-label="View {{ $image['title'] ?: $image['alt'] }} full screen">
                            <x-img :src="$image['image']" :alt="$image['alt']" :sizes="$image['sizes']" :priority="$frame === 0" class="absolute inset-0 h-full w-full object-cover" />
                            <span class="gallery-frame__shade" aria-hidden="true"></span>
                            @if ($image['title'] ?? '')
                                <span class="gallery-frame__caption">
                                    <span class="gallery-frame__index">{{ str_pad($frame + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="gallery-frame__title">{{ $image['title'] }}</span>
                                </span>
                            @endif
                            <span class="gallery-frame__view" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M9 4H4v5M15 4h5v5M9 20H4v-5M15 20h5v-5" stroke="currentColor" stroke-width="1.2"/></svg>
                            </span>
                        </button>
                        @php($frame++)
                    @endforeach
                </div>
            </section>
        @endforeach

        <div x-show="isOpen" x-cloak x-ref="dialog" class="lightbox" role="dialog" aria-modal="true" aria-label="Gallery viewer" tabindex="-1"
            x-transition:enter="lightbox-enter" x-transition:enter-start="lightbox-enter-start" x-transition:leave="lightbox-leave" x-transition:leave-end="lightbox-enter-start"
            @touchstart.passive="touchStart($event)" @touchend.passive="touchEnd($event)">
            <div class="lightbox__bar">
                <p class="lightbox__chapter">
                    <span class="lightbox__mark" aria-hidden="true"></span>
                    <span x-text="current.chapter"></span>
                </p>
                <p class="lightbox__counter" aria-live="polite"><span x-text="pad(index + 1)"></span><span class="lightbox__counter-sep">/</span><span x-text="pad(slides.length)"></span></p>
                <button type="button" class="lightbox__close" @click="close()" x-ref="close">
                    <span class="hidden sm:inline">Close</span>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.2"/></svg>
                </button>
            </div>

            <div class="lightbox__stage" @click.self="close()">
                <button type="button" class="lightbox__arrow lightbox__arrow--prev" @click="prev()" aria-label="Previous image">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12H5m0 0 5-5m-5 5 5 5" stroke="currentColor" stroke-width="1.1"/></svg>
                </button>
                <figure class="lightbox__figure" @click.self="close()">
                    <img :src="current.src" :srcset="current.srcset" sizes="(min-width: 768px) calc(100vw - 14rem), 100vw" :alt="current.alt" class="lightbox__image" :class="fading && 'is-fading'" @load="fading = false" x-on:error="fading = false">
                </figure>
                <button type="button" class="lightbox__arrow lightbox__arrow--next" @click="next()" aria-label="Next image">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h15m0 0-5-5m5 5-5 5" stroke="currentColor" stroke-width="1.1"/></svg>
                </button>
            </div>

            <div class="lightbox__caption" :class="fading && 'is-fading'">
                <h2 class="lightbox__title" x-text="current.title"></h2>
                <p class="lightbox__alt" x-text="current.alt"></p>
            </div>

            <div class="lightbox__thumbs" x-ref="thumbs">
                <template x-for="(slide, i) in slides" :key="i">
                    <button type="button" class="lightbox__thumb" :class="i === index && 'is-active'" @click="go(i)" :aria-label="'Show ' + (slide.title || slide.alt)" :aria-current="i === index">
                        <img :src="slide.thumb" alt="" loading="lazy" decoding="async">
                    </button>
                </template>
            </div>
        </div>
    </div>

    @if ($closing['title'] || $closing['primary']['label'])
        <section class="border-t border-limestone bg-ivory" aria-labelledby="gallery-visit-title">
            <div class="mx-auto grid max-w-[1600px] items-end gap-10 px-5 py-24 md:px-10 md:py-32 lg:grid-cols-12" data-reveal>
                <div class="lg:col-span-7">
                    <div class="flex items-center gap-4">
                        @include('partials.wave-mark-navy')
                        <p class="site-kicker text-navy">{{ $closing['kicker'] }}</p>
                    </div>
                    <h2 id="gallery-visit-title" class="site-display mt-10 text-5xl text-ink md:text-6xl lg:text-7xl">{{ $closing['title'] }} @if ($closing['title_accent'])<span class="accent-navy">{{ $closing['title_accent'] }}</span>@endif</h2>
                </div>
                <div class="flex flex-wrap items-center gap-x-10 gap-y-5 lg:col-span-5 lg:justify-end lg:pb-3">
                    <x-cms-link :link="$closing['secondary']" class="link-navy" />
                    <x-cms-link :link="$closing['primary']" class="site-button site-button-navy" />
                </div>
            </div>
        </section>
    @endif
@endsection
