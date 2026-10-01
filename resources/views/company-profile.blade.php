@extends('layouts.site')

@section('title', 'Company Profile | Jiangsu Hetian Construction')
@section('description', 'Learn about Jiangsu Hetian Construction: its capabilities, qualifications, people, project experience and recognised standards.')

@section('content')
    <section class="relative isolate min-h-[76svh] overflow-hidden bg-navy-deep text-pearl" aria-labelledby="company-profile-title" data-inview>
        <div class="absolute inset-0 -z-10 lg:left-[34%]">
            <x-img src="media/portfolio/leying-steel-structure-factory.webp" alt="Steel-frame factory structure under construction in Nanjing" priority sizes="(min-width: 1024px) 66vw, 100vw" class="settle h-full w-full object-cover object-center" />
            <div class="absolute inset-0 bg-gradient-to-r from-navy-deep via-navy-deep/70 to-navy-deep/20 lg:via-navy-deep/30"></div>
            <div class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-navy-deep/80 to-transparent"></div>
        </div>
        <span class="landmark__frame" aria-hidden="true"></span>

        <div class="relative mx-auto flex min-h-[76svh] max-w-[1600px] flex-col justify-end px-5 pb-14 pt-36 md:px-10 md:pb-20">
            <div class="max-w-4xl" data-reveal>
                <div class="flex items-center gap-4">
                    @include('partials.wave-mark')
                    <p class="site-kicker text-pearl">Company profile · Nanjing, China</p>
                </div>
                <h1 id="company-profile-title" class="site-display mt-9 max-w-4xl text-5xl text-pearl md:text-7xl lg:text-8xl">Jiangsu Hetian<br><span class="display-accent">Construction.</span></h1>
                <p class="mt-7 max-w-2xl font-serif text-2xl leading-snug text-pearl/85 md:text-3xl">Building capability across places, sectors and generations.</p>
            </div>
            <p class="mt-16 border-t border-pearl/25 pt-5 text-xs tracking-[0.16em] text-pearl/70 uppercase">Established 2010 <span class="mx-3 text-gold-soft">/</span> Building · Infrastructure · Development</p>
        </div>
    </section>

    <section class="bg-ivory" aria-labelledby="company-overview-title">
        <div class="mx-auto grid max-w-[1600px] gap-14 px-5 py-24 md:px-10 md:py-36 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-4" data-reveal>
                <p class="site-kicker text-navy">The company</p>
                <h2 id="company-overview-title" class="site-display mt-8 text-5xl text-ink md:text-6xl">A steady foundation.<br><span class="accent-navy">A wider horizon.</span></h2>
            </div>
            <div class="lg:col-span-7 lg:col-start-6" data-reveal>
                <p class="text-[0.98rem] leading-[1.9] text-ink/75">Established in 2010, Jiangsu Hetian Construction Co., Ltd. is an integrated construction enterprise headquartered in Nanjing. Its experience spans building construction, municipal infrastructure, highway subgrade, foundations, steel structures, landscaping, decoration and fit-out.</p>
                <p class="mt-6 text-[0.98rem] leading-[1.9] text-ink/75">The company continues to develop its general contracting and EPC capabilities while expanding from construction into property development and building operations. Through its Kenyan subsidiary, Olmaa Lands Limited, that experience now informs the next chapter at Santorini Residences in Westlands, Nairobi.</p>

                <dl class="mt-12 grid grid-cols-2 gap-x-8 gap-y-8 border-t border-navy/30 pt-8 sm:grid-cols-3">
                    <div>
                        <dt class="site-kicker text-stone">Established</dt>
                        <dd class="mt-3 font-serif text-4xl text-ink md:text-5xl">2010</dd>
                    </div>
                    <div>
                        <dt class="site-kicker text-stone">Registered capital</dt>
                        <dd class="mt-3 font-serif text-4xl text-ink md:text-5xl">53.18m</dd>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <dt class="site-kicker text-stone">Professional personnel</dt>
                        <dd class="mt-3 font-serif text-4xl text-ink md:text-5xl">300+</dd>
                    </div>
                </dl>
                <p class="mt-5 text-xs leading-relaxed text-stone">Personnel figure refers to technical and economic staff with professional titles, as stated in the company profile dated July 2026.</p>
            </div>
        </div>
    </section>

    <section class="bg-paper" aria-labelledby="capabilities-title">
        <div class="mx-auto max-w-[1600px] px-5 py-24 md:px-10 md:py-36">
            <div class="grid gap-8 md:grid-cols-12 md:items-end" data-reveal>
                <div class="md:col-span-7">
                    <p class="site-kicker text-navy">A connected set of disciplines</p>
                    <h2 id="capabilities-title" class="site-display mt-7 text-5xl text-ink md:text-7xl">Capability, built in layers.</h2>
                </div>
                <p class="max-w-md text-sm leading-[1.85] text-stone md:col-span-4 md:col-start-9">From the groundworks to the finished space, the company brings complementary construction and specialist services together.</p>
            </div>

            <div class="mt-16 grid gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-3" data-reveal>
                <article class="border-t border-navy/35 pt-6">
                    <span class="font-serif text-lg text-navy">01</span>
                    <h3 class="mt-5 font-serif text-3xl text-ink">Building & municipal works</h3>
                    <p class="mt-4 text-sm leading-[1.85] text-ink/70">Grade II general contracting qualifications for building construction and municipal public works.</p>
                </article>
                <article class="border-t border-navy/35 pt-6">
                    <span class="font-serif text-lg text-navy">02</span>
                    <h3 class="mt-5 font-serif text-3xl text-ink">Highway subgrade</h3>
                    <p class="mt-4 text-sm leading-[1.85] text-ink/70">Grade II specialized contracting for highway subgrade works, alongside road construction and renewal.</p>
                </article>
                <article class="border-t border-navy/35 pt-6">
                    <span class="font-serif text-lg text-navy">03</span>
                    <h3 class="mt-5 font-serif text-3xl text-ink">Foundations & substructure</h3>
                    <p class="mt-4 text-sm leading-[1.85] text-ink/70">Specialist foundation, pile and substructure works, including major pile foundation and diaphragm wall projects.</p>
                </article>
                <article class="border-t border-navy/35 pt-6">
                    <span class="font-serif text-lg text-navy">04</span>
                    <h3 class="mt-5 font-serif text-3xl text-ink">Steel structures</h3>
                    <p class="mt-4 text-sm leading-[1.85] text-ink/70">Steel-frame buildings, structural steel, canopies and associated specialist construction.</p>
                </article>
                <article class="border-t border-navy/35 pt-6">
                    <span class="font-serif text-lg text-navy">05</span>
                    <h3 class="mt-5 font-serif text-3xl text-ink">Decoration & fit-out</h3>
                    <p class="mt-4 text-sm leading-[1.85] text-ink/70">Building decoration and renovation, interior upgrading, repair, installation and associated works.</p>
                </article>
                <article class="border-t border-navy/35 pt-6">
                    <span class="font-serif text-lg text-navy">06</span>
                    <h3 class="mt-5 font-serif text-3xl text-ink">EPC & development</h3>
                    <p class="mt-4 text-sm leading-[1.85] text-ink/70">Expanding from contracting into integrated engineering, procurement and construction, development and building operations.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-navy-deep text-pearl" aria-labelledby="people-title">
        <div class="mx-auto grid max-w-[1600px] gap-14 px-5 py-24 md:px-10 md:py-36 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-4" data-reveal>
                <p class="site-kicker text-gold-soft">People & practice</p>
                <h2 id="people-title" class="site-display mt-8 text-5xl text-pearl md:text-6xl">The detail behind delivery.</h2>
                <p class="mt-8 max-w-md text-sm leading-[1.9] text-pearl/70">The company profile describes a skilled team with a strong emphasis on professional qualifications, safe working and quality construction.</p>
            </div>
            <div class="lg:col-span-7 lg:col-start-6" data-reveal>
                <dl class="grid grid-cols-2 gap-x-8 gap-y-9 border-t border-pearl/25 pt-8 sm:grid-cols-3">
                    <div><dt class="site-kicker text-pearl/55">Senior professional titles</dt><dd class="mt-3 font-serif text-4xl text-pearl">15</dd></div>
                    <div><dt class="site-kicker text-pearl/55">Intermediate titles</dt><dd class="mt-3 font-serif text-4xl text-pearl">50+</dd></div>
                    <div><dt class="site-kicker text-pearl/55">Special trades certified</dt><dd class="mt-3 font-serif text-4xl text-pearl">100%</dd></div>
                    <div class="col-span-2 border-t border-pearl/20 pt-7 sm:col-span-1 sm:border-t-0 sm:pt-0"><dt class="site-kicker text-pearl/55">Technical trades certified</dt><dd class="mt-3 font-serif text-4xl text-pearl">Over 90%</dd></div>
                </dl>
                <p class="mt-7 max-w-2xl text-xs leading-relaxed text-pearl/50">Figures are reported in the company profile dated July 2026; certifications refer to the company’s stated on-duty and technical-trade certification rates.</p>
            </div>
        </div>
    </section>

    <section class="bg-ivory" aria-labelledby="recognition-title">
        <div class="mx-auto grid max-w-[1600px] gap-14 px-5 py-24 md:px-10 md:py-36 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-5" data-reveal>
                <p class="site-kicker text-navy">Recognition & standards</p>
                <h2 id="recognition-title" class="site-display mt-8 text-5xl text-ink md:text-6xl">A reputation earned over time.</h2>
                <p class="mt-8 max-w-lg text-sm leading-[1.9] text-ink/70">The company profile records recognition for contract integrity, creditworthiness, construction quality and site practice.</p>
            </div>
            <div class="lg:col-span-6 lg:col-start-7" data-reveal>
                <ul class="border-t border-navy/30">
                    <li class="grid grid-cols-[3rem_1fr] gap-4 border-b border-navy/20 py-5"><span class="font-serif text-lg text-navy">01</span><span class="font-serif text-2xl text-ink">Jinling Cup</span></li>
                    <li class="grid grid-cols-[3rem_1fr] gap-4 border-b border-navy/20 py-5"><span class="font-serif text-lg text-navy">02</span><span class="font-serif text-2xl text-ink">Yangzi Cup</span></li>
                    <li class="grid grid-cols-[3rem_1fr] gap-4 border-b border-navy/20 py-5"><span class="font-serif text-lg text-navy">03</span><span class="font-serif text-2xl text-ink">Provincial Civilized Construction Site</span></li>
                    <li class="grid grid-cols-[3rem_1fr] gap-4 border-b border-navy/20 py-5"><span class="font-serif text-lg text-navy">04</span><span class="font-serif text-2xl text-ink">High-Quality Structural Engineering</span></li>
                    <li class="grid grid-cols-[3rem_1fr] gap-4 border-b border-navy/20 py-5"><span class="font-serif text-lg text-navy">05</span><span class="font-serif text-2xl text-ink">Dust-Free Construction Site & Advanced Collective</span></li>
                </ul>
                <p class="mt-7 text-sm leading-[1.8] text-stone">The profile also records an AAA credit rating and recognition as an Enterprise Honoring Contracts and Keeping Promises.</p>
            </div>
        </div>
    </section>

    <section class="border-t border-limestone bg-paper" aria-labelledby="culture-title">
        <div class="mx-auto grid max-w-[1600px] gap-12 px-5 py-24 md:px-10 md:py-32 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-5" data-reveal>
                <p class="site-kicker text-navy">Guiding principles</p>
                <h2 id="culture-title" class="site-display mt-8 text-5xl text-ink md:text-6xl">A way of working.</h2>
            </div>
            <ul class="flex flex-wrap gap-x-8 gap-y-5 lg:col-span-6 lg:col-start-7" data-reveal>
                @foreach (['Integrity', 'Innovation', 'Responsibility', 'Attitude', 'Pragmatism', 'Teamwork'] as $value)
                    <li class="border-b border-champagne/60 pb-2 font-serif text-2xl text-ink md:text-3xl">{{ $value }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="relative overflow-hidden bg-navy-deep text-pearl" aria-labelledby="profile-closing-title">
        <div class="mx-auto grid max-w-[1600px] items-end gap-12 px-5 py-24 md:px-10 md:py-32 lg:grid-cols-12">
            <div class="lg:col-span-8" data-reveal>
                <div class="flex items-center gap-4">
                    @include('partials.wave-mark')
                    <p class="site-kicker text-pearl">From Jiangsu to Nairobi</p>
                </div>
                <h2 id="profile-closing-title" class="site-display mt-9 max-w-4xl text-5xl text-pearl md:text-7xl">The next chapter is taking shape.</h2>
                <p class="mt-7 max-w-2xl text-base leading-[1.85] text-pearl/70">See the work behind the partnership and how its experience is being brought to Santorini Residences.</p>
            </div>
            <div class="flex flex-wrap items-center gap-x-8 gap-y-5 lg:col-span-4 lg:justify-end" data-reveal>
                <a href="{{ route('portfolio') }}" class="link-line">View the portfolio</a>
                <a href="{{ route('about') }}" class="site-button">The house</a>
            </div>
        </div>
    </section>
@endsection
