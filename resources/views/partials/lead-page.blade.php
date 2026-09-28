{{-- Split lead page: sticky image panel with caption, introduction and a lead form. --}}
<section class="grid min-h-screen items-start lg:grid-cols-2">
    <figure class="relative hidden overflow-hidden bg-navy-deep lg:sticky lg:top-0 lg:block lg:h-screen" data-inview>
        <x-img :src="$aside['image']" :alt="$aside['image_alt']" sizes="50vw" class="settle absolute inset-0 h-full w-full object-cover" />
        <div class="absolute inset-x-0 bottom-0 h-3/5 bg-gradient-to-t from-navy-deep/95 via-navy-deep/55 to-transparent"></div>
        <span class="enquire__frame" aria-hidden="true"></span>
        <figcaption class="absolute inset-x-0 bottom-0 px-16 pb-16 text-pearl">
            <div class="flex items-center gap-4">
                @include('partials.wave-mark')
                <span class="site-kicker text-pearl">{{ $aside['kicker'] }}</span>
            </div>
            <p class="mt-6 max-w-md font-serif text-3xl leading-snug">{{ $aside['place'] }} @if ($aside['place_accent'])<span class="display-accent">{{ $aside['place_accent'] }}</span>@endif</p>
            @if ($aside['offers'])
                <ul class="mt-8 flex flex-wrap gap-x-8 gap-y-3 border-t border-champagne/40 pt-6">
                    @foreach ($aside['offers'] as $offer)
                        <li class="flex items-center gap-3 text-sm tracking-[0.04em] text-silver/80">
                            <span class="h-px w-4 bg-champagne" aria-hidden="true"></span>
                            {{ $offer }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </figcaption>
    </figure>

    <div class="px-5 pb-28 pt-36 md:px-14 md:pt-44 xl:px-20">
        <div data-reveal>
            <div class="flex items-center gap-4">
                @include('partials.wave-mark-navy')
                <p class="site-kicker text-navy">{{ $intro['kicker'] }}</p>
            </div>
            <h1 class="site-display mt-10 text-5xl text-ink md:text-7xl">{{ $intro['title'] }} @if ($intro['title_accent'])<span class="accent-navy">{{ $intro['title_accent'] }}</span>@endif</h1>
            @if ($intro['body'])
                <p class="mt-8 max-w-md text-[0.95rem] leading-[1.85] text-ink/72">{{ $intro['body'] }}</p>
            @endif
            @if (! empty($intro['visit']))
                <x-cms-link :link="$intro['visit']" class="link-gold link-gold--navy mt-8"><span class="link-gold__line" aria-hidden="true"></span></x-cms-link>
            @endif
        </div>
        <div class="mt-16" data-reveal>
            @include('partials.lead-form', ['form' => $form, 'preset' => $preset ?? [], 'funnel' => $funnel ?? null])
        </div>
    </div>
</section>
