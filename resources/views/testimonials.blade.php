@extends('layouts.site')

@section('title', 'Testimonials | Santorini Residences, Westlands Nairobi')
@section('description', 'Read testimonials and stories from people connected with Santorini Residences in Westlands, Nairobi.')
@section('canonical', route('testimonials'))

@section('content')
    <section class="relative isolate flex min-h-[58svh] items-end overflow-hidden bg-navy-deep text-pearl" aria-labelledby="testimonials-title">
        <x-img src="media/tower-sunset.webp" alt="Santorini Residences at sunset in Westlands, Nairobi." priority class="absolute inset-0 -z-10 h-full w-full object-cover object-center" />
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-navy-deep/85 via-navy-deep/50 to-navy-deep/15"></div>
        <div class="absolute inset-x-0 bottom-0 -z-10 h-2/3 bg-gradient-to-t from-navy-deep/80 to-transparent"></div>
        <div class="mx-auto w-full max-w-[1600px] px-5 pb-16 pt-36 md:px-10 md:pb-24">
            <p class="site-kicker text-pearl/70">Santorini Residences · Voices</p>
            <h1 id="testimonials-title" class="site-display mt-6 max-w-4xl text-6xl md:text-8xl">Testimonials</h1>
            <p class="mt-7 max-w-2xl font-serif text-2xl leading-snug text-pearl/90 md:text-3xl">Perspectives from people who know Santorini Residences.</p>
        </div>
    </section>

    <section class="mx-auto max-w-[1200px] px-5 py-20 md:px-10 md:py-32" aria-label="Published testimonials">
        <div class="grid gap-8 md:grid-cols-2">
            @foreach ($testimonials as $item)
                <article class="border-t border-navy/20 pt-7" data-reveal>
                    <p class="site-kicker text-stone">{{ $item['type'] }}</p>
                    @if (filled($item['headline'] ?? null))<h2 class="mt-4 font-serif text-3xl leading-tight text-ink">{{ $item['headline'] }}</h2>@endif
                    <blockquote class="mt-5 font-serif text-xl leading-relaxed text-ink/80">“{{ $item['content'] }}”</blockquote>
                    <p class="mt-6 text-sm font-medium tracking-wide text-navy">{{ $item['name'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
