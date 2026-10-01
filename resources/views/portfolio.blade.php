@extends('layouts.site')

@php
    $periods = \App\Support\ProjectPortfolio::periods();
@endphp

@section('title', 'Project Portfolio | Jiangsu Hetian Construction')
@section('description', 'Explore selected residential, commercial, civic, education and infrastructure projects delivered by Jiangsu Hetian Construction from 2012 to 2025.')

@section('content')
    <section class="relative isolate min-h-[82svh] overflow-hidden bg-navy-deep text-pearl" aria-labelledby="portfolio-title" data-inview>
        <div class="absolute inset-0 -z-10 lg:left-[36%]">
            <x-img src="media/portfolio/zhongju-tower.webp" alt="Architectural rendering of Zhongju Tower, an office project in Nanjing" priority sizes="(min-width: 1024px) 64vw, 100vw" class="settle h-full w-full object-cover object-center" />
            <div class="absolute inset-0 bg-gradient-to-r from-navy-deep via-navy-deep/70 to-navy-deep/15 lg:via-navy-deep/35"></div>
            <div class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-navy-deep/75 to-transparent"></div>
        </div>
        <span class="landmark__frame" aria-hidden="true"></span>

        <div class="relative mx-auto flex min-h-[82svh] max-w-[1600px] flex-col justify-end px-5 pb-12 pt-36 md:px-10 md:pb-16 lg:pb-20">
            <div class="max-w-4xl" data-reveal>
                <div class="flex items-center gap-4">
                    @include('partials.wave-mark')
                    <p class="site-kicker text-pearl">Jiangsu Hetian Construction Co., Ltd.</p>
                </div>
                <h1 id="portfolio-title" class="site-display mt-9 max-w-4xl text-6xl text-pearl md:text-8xl">Experience,<br><span class="display-accent">made visible.</span></h1>
                <p class="mt-8 max-w-xl font-serif text-2xl leading-snug text-pearl/85 md:text-3xl">A considered record of places built, renewed and brought to life.</p>
            </div>

            <div class="mt-16 flex flex-wrap items-end justify-between gap-6 border-t border-pearl/25 pt-5 text-xs tracking-[0.16em] text-pearl/70 uppercase">
                <span>Selected works · Nanjing and beyond</span>
                <a href="#project-register" class="group inline-flex items-center gap-4 text-pearl transition hover:text-gold-soft">
                    Explore the work
                    <svg class="h-5 w-5 transition-transform group-hover:translate-y-1" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v17m0 0 6-6m-6 6-6-6" stroke="currentColor" stroke-width="1.2"/></svg>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-ivory" aria-labelledby="portfolio-intro-title">
        <div class="mx-auto grid max-w-[1600px] gap-14 px-5 py-24 md:px-10 md:py-36 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-4" data-reveal>
                <p class="site-kicker text-navy">A foundation of delivery</p>
                <h2 id="portfolio-intro-title" class="site-display mt-8 text-5xl text-ink md:text-6xl">Built on experience.<br><span class="accent-navy">Growing with purpose.</span></h2>
            </div>
            <div class="lg:col-span-7 lg:col-start-6" data-reveal>
                <p class="text-[0.98rem] leading-[1.9] text-ink/75">Founded in 2010 and headquartered in Nanjing, Jiangsu Hetian Construction brings more than fifteen years of experience across residential, commercial, institutional, public and industrial work. Its capabilities span building construction, municipal infrastructure, foundation engineering, steel structures, roadworks, decoration and fit-out.</p>
                <p class="mt-6 text-[0.98rem] leading-[1.9] text-ink/75">Through its Kenyan subsidiary, Olmaa Lands Limited, the Group is bringing that experience to Nairobi with Santorini Residences, a contemporary mixed-use development on Lantana Road, Westlands.</p>
                <a href="{{ route('company-profile') }}" class="link-navy mt-8 inline-flex">Read the company profile <span class="ml-3" aria-hidden="true">→</span></a>

                <dl class="mt-12 grid grid-cols-2 gap-x-8 gap-y-8 border-t border-navy/30 pt-8 sm:grid-cols-3">
                    <div>
                        <dt class="site-kicker text-stone">Established</dt>
                        <dd class="mt-3 font-serif text-4xl text-ink md:text-5xl">2010</dd>
                    </div>
                    <div>
                        <dt class="site-kicker text-stone">Project register</dt>
                        <dd class="mt-3 font-serif text-4xl text-ink md:text-5xl">2012–2025</dd>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <dt class="site-kicker text-stone">Major works</dt>
                        <dd class="mt-3 font-serif text-4xl text-ink md:text-5xl">Close to 20</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section id="project-register" class="scroll-mt-24 bg-paper" aria-labelledby="register-title">
        <div class="mx-auto max-w-[1600px] px-5 pb-12 pt-24 md:px-10 md:pb-16 md:pt-32">
            <div class="grid gap-8 md:grid-cols-12 md:items-end" data-reveal>
                <div class="md:col-span-7">
                    <p class="site-kicker text-navy">A selection from the project register</p>
                    <h2 id="register-title" class="site-display mt-7 text-5xl text-ink md:text-7xl">The work, in time.</h2>
                </div>
                <p class="max-w-md text-sm leading-[1.85] text-stone md:col-span-4 md:col-start-9">From homes and campuses to civic places and specialist facilities, each project reflects a commitment to detail and delivery.</p>
            </div>

            <nav class="mt-12 flex flex-wrap gap-x-7 gap-y-3 border-y border-navy/20 py-5" aria-label="Jump to a project period">
                @foreach ($periods as $period)
                    <a href="#period-{{ $period['id'] }}" class="inline-flex items-center gap-3 text-xs tracking-[0.12em] text-stone uppercase transition hover:text-navy">
                        <span class="font-serif text-lg text-navy">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        {{ $period['period'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        @php
            $projectNumber = 0;
        @endphp
        @foreach ($periods as $period)
            <section id="period-{{ $period['id'] }}" class="mx-auto max-w-[1600px] scroll-mt-24 px-5 pb-20 md:px-10 md:pb-28" aria-labelledby="period-title-{{ $period['id'] }}">
                <header class="mb-8 grid gap-4 border-t border-navy/30 pt-5 sm:grid-cols-12 sm:items-baseline" data-reveal>
                    <p class="site-kicker text-navy sm:col-span-2">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} / Period</p>
                    <h3 id="period-title-{{ $period['id'] }}" class="font-serif text-3xl text-ink sm:col-span-4 md:text-4xl">{{ $period['period'] }}</h3>
                    <p class="text-sm text-stone sm:col-span-5 sm:col-start-8 sm:text-right">{{ $period['note'] }}</p>
                </header>

                <div class="grid grid-cols-1 gap-x-5 gap-y-12 md:grid-cols-2 md:gap-x-7 md:gap-y-16 lg:grid-cols-12 lg:gap-x-8">
                    @foreach ($period['projects'] as $project)
                        @php
                            $projectNumber++;
                            $span = in_array($loop->iteration % 4, [1, 0], true) ? 'lg:col-span-7' : 'lg:col-span-5';
                            $figureRatio = in_array($loop->iteration % 4, [1, 0], true) ? 'aspect-[1.32]' : 'aspect-[1.12]';
                        @endphp
                        <article class="group {{ $span }}" data-reveal>
                            <figure class="project-frame relative {{ $figureRatio }} overflow-hidden bg-[#e4ddd2]">
                                @if ($project['image'])
                                    <x-img :src="'media/portfolio/'.$project['image'].'.webp'" :alt="$project['alt']" sizes="(min-width: 1024px) 58vw, (min-width: 768px) 50vw, 100vw" class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.035]" />
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-ink/55 via-transparent to-transparent opacity-70"></div>
                                    <span class="absolute bottom-5 left-5 font-serif text-lg text-white/90">{{ str_pad($projectNumber, 2, '0', STR_PAD_LEFT) }}</span>
                                @else
                                    <div class="absolute inset-0 flex flex-col justify-between overflow-hidden bg-gradient-to-br from-navy-deep to-navy-soft p-7 text-pearl md:p-9">
                                        <span class="site-kicker text-gold-soft">Project record · {{ str_pad($projectNumber, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="pointer-events-none absolute -right-4 top-1/4 font-serif text-[11rem] leading-none text-white/[0.035]" aria-hidden="true">禾</span>
                                        <div class="relative">
                                            @if ($project['local'])
                                                <p lang="zh" class="font-serif text-xl text-gold-soft/80">{{ $project['local'] }}</p>
                                            @endif
                                            <p class="mt-4 max-w-lg font-serif text-3xl leading-tight md:text-4xl">{{ $project['name'] }}</p>
                                        </div>
                                        <span class="site-kicker text-pearl/55">{{ $project['sector'] }}</span>
                                    </div>
                                @endif
                            </figure>

                            <div class="grid gap-x-6 gap-y-3 pt-5 sm:grid-cols-[1fr_auto]">
                                <div>
                                    <p class="site-kicker text-stone">{{ $project['date'] }} <span class="mx-2 text-champagne">/</span> {{ $project['sector'] }}</p>
                                    <h4 class="mt-3 max-w-2xl font-serif text-2xl leading-tight text-ink md:text-3xl">{{ $project['name'] }}</h4>
                                    @if ($project['local'])
                                        <p lang="zh" class="mt-2 font-serif text-lg text-stone/80">{{ $project['local'] }}</p>
                                    @endif
                                </div>
                                <div class="border-t border-navy/20 pt-3 text-sm leading-relaxed text-stone sm:mt-1 sm:min-w-44 sm:border-t-0 sm:border-l sm:pl-5 sm:pt-0">
                                    @if ($project['location'])
                                        <p>{{ $project['location'] }}</p>
                                    @endif
                                    <p class="mt-2 text-ink/75">{{ $project['area'] }}</p>
                                </div>
                                @if ($project['summary'])
                                    <p class="max-w-3xl text-sm leading-[1.8] text-ink/70 sm:col-span-2">{{ $project['summary'] }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    </section>

    <section class="relative overflow-hidden bg-navy-deep text-pearl" aria-labelledby="portfolio-close-title">
        <div class="mx-auto grid max-w-[1600px] items-end gap-12 px-5 py-24 md:px-10 md:py-32 lg:grid-cols-12">
            <div class="lg:col-span-8" data-reveal>
                <div class="flex items-center gap-4">
                    @include('partials.wave-mark')
                    <p class="site-kicker text-pearl">From Jiangsu to Nairobi</p>
                </div>
                <h2 id="portfolio-close-title" class="site-display mt-9 max-w-4xl text-5xl text-pearl md:text-7xl">A considered next chapter.</h2>
                <p class="mt-7 max-w-2xl text-base leading-[1.85] text-pearl/70">Discover how this experience is taking shape at Santorini Residences, Lantana Road, Westlands.</p>
            </div>
            <div class="flex flex-wrap items-center gap-x-8 gap-y-5 lg:col-span-4 lg:justify-end" data-reveal>
                <a href="{{ route('company-profile') }}" class="link-line">Company profile</a>
                <a href="{{ route('about') }}" class="link-line">Meet the house</a>
                <a href="{{ route('enquire') }}" class="site-button">Enquire now</a>
            </div>
        </div>
    </section>
@endsection
