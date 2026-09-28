@extends('layouts.site')

@section('title', 'Santorini Residences Westlands | Luxury Apartments in Nairobi')
@section('description', 'Discover Santorini Residences on Lantana Road, Westlands. Premium 1, 2 and 3-bedroom apartments and exclusive loft residences with resort-inspired amenities in Nairobi.')

@section('canonical', route('home'))

@push('head')
<meta name="robots" content="noindex, follow">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ApartmentComplex',
    'name' => 'Santorini Residences',
    'description' => 'A landmark residential development on Lantana Road, Westlands, Nairobi. 328 residences, including 1, 2 and 3-bedroom homes and exclusive loft residences.',
    'url' => url('/'),
    'image' => url('media/hero-night.webp'),
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
            <x-img src="media/hero-night.webp" alt="Santorini Residences at night, a curved illuminated tower above the Nairobi skyline." priority class="hero-still absolute inset-0 h-full w-full object-cover object-[center_42%]" />
            <video class="hero-film absolute inset-0 h-full w-full object-cover object-center" data-hero-film data-src="{{ asset('media/hero-film-1080.mp4') }}" data-src-mobile="{{ asset('media/hero-film-720.mp4') }}" muted loop playsinline preload="none" aria-hidden="true"></video>
            <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/25 to-ink/30"></div>
        </div>
        <div class="relative mx-auto flex min-h-[100svh] w-full min-w-0 max-w-[1600px] flex-col justify-end px-5 pb-12 pt-32 md:px-10 md:pb-16">
            <p class="site-kicker text-white/75">Lantana Road, Westlands, Nairobi</p>
            <h1 class="site-display mt-6 max-w-5xl text-[18vw] text-white sm:text-8xl md:text-[8.5rem]">Santorini</h1>
            <p class="mt-6 max-w-[15.5rem] font-serif text-2xl leading-snug text-white/90 sm:max-w-xl sm:text-3xl">A new landmark of urban resort living.</p>
            <p class="mt-4 max-w-[15.5rem] text-sm leading-relaxed tracking-wide text-white/75 sm:max-w-lg sm:text-base">Contemporary residences. Distinctive architecture. Resort-inspired living.</p>
            <div class="mt-10 flex flex-col items-start gap-5 sm:flex-row sm:items-center">
                <a href="{{ route('residences') }}" class="site-button site-button-on-dark">Explore the residences</a>
                <a href="{{ route('enquire', ['interest' => 'private-viewing']) }}" class="link-line text-[0.68rem] tracking-[0.16em] uppercase sm:tracking-[0.2em]">Book a private viewing</a>
            </div>
            <dl class="mt-14 grid grid-cols-3 gap-6 border-t border-white/20 pt-6 text-white/80 md:max-w-2xl">
                <div>
                    <dt class="site-kicker text-[0.58rem] text-white/55">Residences</dt>
                    <dd class="mt-2 font-serif text-3xl md:text-4xl">328</dd>
                </div>
                <div>
                    <dt class="site-kicker text-[0.58rem] text-white/55">Towers</dt>
                    <dd class="mt-2 font-serif text-3xl md:text-4xl">G+19</dd>
                </div>
                <div>
                    <dt class="site-kicker text-[0.58rem] text-white/55">Acres</dt>
                    <dd class="mt-2 font-serif text-3xl md:text-4xl">0.76</dd>
                </div>
            </dl>
        </div>
    </section>

    <section id="project" class="aegean relative overflow-hidden" aria-labelledby="project-title">
        <div class="mx-auto max-w-[1600px] px-5 pb-24 pt-24 md:px-10 md:pb-32 md:pt-32 lg:pb-40 lg:pt-40">
            <div class="grid gap-16 lg:grid-cols-12 lg:gap-10">
                <div class="lg:col-span-6 lg:pt-6" data-reveal>
                    <div class="section-index">
                        <span class="section-index__number">01</span>
                        @include('partials.wave-mark')
                        <p class="site-kicker text-pewter">The project</p>
                    </div>

                    <h2 id="project-title" class="site-display mt-10 text-[2.9rem] text-pearl sm:text-6xl lg:text-[5.25rem]">
                        Where architecture meets <span class="display-accent">everyday life.</span>
                    </h2>

                    <p class="mt-12 max-w-xl font-serif text-2xl leading-snug text-silver md:text-[1.7rem]">
                        On Lantana Road in Westlands, Nairobi, Santorini introduces a new approach to premium urban living: distinctive architecture, thoughtfully designed residences, and elevated lifestyle amenities, all in one address.
                    </p>
                    <p class="mt-8 max-w-lg text-base leading-relaxed text-pewter">
                        Santorini is the flagship development of LOVE HOMES, the brand’s first landmark project in Nairobi. Inspired by the relaxed character of Santorini, Greece, it brings contemporary architecture and resort-style amenities into the heart of Westlands.
                    </p>

                    <a href="#landmark" class="link-gold mt-12">
                        <span class="link-gold__line" aria-hidden="true"></span>
                        Discover the landmark
                    </a>
                </div>

                <figure class="sail-frame mx-auto w-full max-w-[34rem] lg:col-span-5 lg:col-start-8 lg:mt-24 lg:max-w-none" data-reveal>
                    <div class="sail-frame__media aspect-[4/5]">
                        <x-img src="media/tower-front.webp" alt="Santorini Residences at dusk, the curved white frame and glass crown of the tower above Westlands." sizes="(min-width: 1024px) 42vw, 34rem" class="object-[53%_center]" />
                    </div>
                    <figcaption class="mt-5 flex items-center justify-between gap-6 text-[0.68rem] tracking-[0.2em] text-pewter uppercase">
                        <span>Lantana Road, Westlands</span>
                        <span class="text-gold">G+19 twin towers</span>
                    </figcaption>
                </figure>
            </div>

            <dl class="mt-24 grid grid-cols-2 gap-x-6 gap-y-14 md:mt-32 lg:grid-cols-4" data-reveal>
                <div class="figure-stat flex flex-col-reverse justify-end">
                    <dt class="site-kicker mt-5 text-pewter">Bedroom residences</dt>
                    <dd class="figure-stat__value text-5xl text-pearl md:text-6xl">1, 2 &amp; 3</dd>
                </div>
                <div class="figure-stat flex flex-col-reverse justify-end">
                    <dt class="site-kicker mt-5 text-pewter">Lofts on the 19th floor</dt>
                    <dd class="figure-stat__value text-5xl text-pearl md:text-6xl">22</dd>
                </div>
                <div class="figure-stat flex flex-col-reverse justify-end">
                    <dt class="site-kicker mt-5 text-pewter">Of construction</dt>
                    <dd class="figure-stat__value text-5xl text-pearl md:text-6xl">30,000+<span class="figure-stat__unit">m²</span></dd>
                </div>
                <div class="figure-stat flex flex-col-reverse justify-end">
                    <dt class="site-kicker mt-5 text-pewter">Parking bays</dt>
                    <dd class="figure-stat__value text-5xl text-pearl md:text-6xl">302</dd>
                </div>
            </dl>
        </div>
    </section>

    <section id="landmark" class="relative min-h-[88vh] bg-ink text-white">
        <x-img src="media/tower-sunset.webp" alt="Santorini Residences at sunset, the white curved frame of the tower against a rose sky." class="absolute inset-0 h-full w-full object-cover object-center" />
        <div class="absolute inset-0 bg-gradient-to-r from-ink/75 via-ink/25 to-transparent"></div>
        <div class="relative flex min-h-[88vh] max-w-3xl flex-col justify-end px-5 py-20 md:px-10 md:py-28" data-reveal>
            <p class="site-kicker text-white/70">Designed to be recognised</p>
            <h2 class="site-display mt-6 text-5xl md:text-7xl">A building with a signature.</h2>
            <p class="mt-6 max-w-xl text-base leading-relaxed text-white/80 md:text-lg">The twin-tower façade moves away from the conventional straight residential block. Flowing curves, expansive glazing, and sculptural aluminium give the silhouette a distinct identity by day, and architectural lighting gives it another after dark. The language draws on the movement of a sail, and on the curves associated with Santorini itself.</p>
        </div>
    </section>

    <section class="bg-ivory">
        <div class="grid lg:grid-cols-2">
            <figure class="media-zoom min-h-[60vh] lg:min-h-[100vh]">
                <x-img src="media/facade-curves.webp" alt="Curved aluminium balcony lines and large-format glazing along the Santorini façade." sizes="(min-width: 1024px) 50vw, 100vw" class="h-full w-full object-cover" />
            </figure>
            <div class="flex flex-col justify-center px-5 py-20 md:px-14 lg:py-28" data-reveal>
                <p class="site-kicker text-stone">The façade</p>
                <h2 class="site-display mt-6 text-5xl md:text-6xl">A signature architectural identity.</h2>
                <dl class="mt-14 space-y-8">
                    <div class="border-t border-limestone pt-6">
                        <dt class="font-serif text-3xl">Glass</dt>
                        <dd class="mt-2 max-w-md text-sm leading-relaxed text-stone">Large-format, Low-E insulated glazing. A contemporary surface that supports thermal performance, natural light, and views.</dd>
                    </div>
                    <div class="border-t border-limestone pt-6">
                        <dt class="font-serif text-3xl">Aluminium</dt>
                        <dd class="mt-2 max-w-md text-sm leading-relaxed text-stone">Curved aluminium panels follow the flowing geometry of the towers and form a key part of the building’s identity.</dd>
                    </div>
                    <div class="border-t border-limestone pt-6">
                        <dt class="font-serif text-3xl">Light</dt>
                        <dd class="mt-2 max-w-md text-sm leading-relaxed text-stone">Integrated architectural lighting accentuates the curves and form after sunset.</dd>
                    </div>
                    <div class="border-t border-limestone pt-6">
                        <dt class="font-serif text-3xl">Podium</dt>
                        <dd class="mt-2 max-w-md text-sm leading-relaxed text-stone">A curved podium connects the towers to the ground-level commercial and arrival spaces.</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section id="distinctions" class="mx-auto max-w-[1600px] px-5 py-24 md:px-10 md:py-36">
        <div class="max-w-3xl" data-reveal>
            <p class="site-kicker text-stone">Eight distinctions</p>
            <h2 class="site-display mt-6 text-5xl md:text-7xl">Not simply a home. A more complete way of living.</h2>
        </div>

        <div class="mt-20">
            @foreach (\App\Support\SiteContent::features() as $feature)
                <article class="grid gap-8 border-t border-limestone py-16 md:grid-cols-12 md:gap-10 md:py-24" data-reveal>
                    <p class="font-serif text-4xl text-stone md:col-span-2">{{ $feature['n'] }}</p>
                    <div class="md:col-span-4">
                        <h3 class="font-serif text-4xl leading-none md:text-5xl">{{ $feature['title'] }}</h3>
                        <p class="mt-4 text-sm leading-relaxed text-stone">{{ $feature['line'] }}</p>
                    </div>
                    <p class="text-base leading-relaxed text-ink/85 md:col-span-6 md:text-lg">{{ $feature['body'] }}</p>
                    @if ($feature['image'])
                        <figure class="media-zoom md:col-span-12 mt-4">
                            <x-img :src="$feature['image']" :alt="$feature['alt']" class="aspect-[16/9] w-full object-cover md:aspect-[21/9]" />
                        </figure>
                    @endif
                </article>
            @endforeach
        </div>
        <p class="max-w-3xl border-t border-limestone pt-12 font-serif text-3xl leading-snug md:text-4xl">Distinctive architecture. Integrated retail. Elevated dining. Dedicated wellness. Thoughtful engineering. High-end management.</p>
    </section>

    <section id="residences" class="bg-ink text-paper">
        <div class="mx-auto grid max-w-[1600px] gap-16 px-5 py-24 md:px-10 md:py-32 lg:grid-cols-12">
            <div class="lg:col-span-5" data-reveal>
                <p class="site-kicker text-sand">Residences</p>
                <h2 class="site-display mt-6 text-5xl md:text-6xl">Designed around different ways of living.</h2>
                <p class="mt-6 max-w-md text-base leading-relaxed text-sand">A curated collection of one, two, and three-bedroom homes, with a limited series of loft residences on the 19th floor.</p>
                <a href="{{ route('residences') }}" class="site-button site-button-on-dark mt-10">View the residences</a>
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
        <figure class="media-zoom">
            <x-img src="media/living-wide.webp" alt="A residence living room in pale stone and timber, opening toward the kitchen." class="max-h-[80vh] w-full object-cover" />
        </figure>
    </section>

    <section id="experience">
        <div class="grid lg:grid-cols-12">
            <figure class="media-zoom relative min-h-[78vh] lg:col-span-7">
                <x-img src="media/indoor-pool.webp" alt="Indoor pool with a mosaic rim, daybeds, and glazing toward the city at night." sizes="(min-width: 1024px) 58vw, 100vw" class="absolute inset-0 h-full w-full object-cover" />
            </figure>
            <div class="flex flex-col justify-between bg-paper px-5 py-16 md:px-12 lg:col-span-5 lg:py-20" data-reveal>
                <div>
                    <p class="site-kicker text-stone">Swim</p>
                    <h2 class="site-display mt-5 text-5xl md:text-6xl">Resort living, in the city.</h2>
                    <p class="mt-6 max-w-md leading-relaxed text-ink/80">A temperature-controlled sky pool, and an indoor pool lounge for evenings when the city is the view.</p>
                </div>
                <a href="{{ route('gallery') }}" class="link-line mt-12 w-fit text-[0.7rem] tracking-[0.18em] uppercase">Explore the experience</a>
            </div>
        </div>

        <div class="grid md:grid-cols-2">
            <figure class="media-zoom relative min-h-[70vh]">
                <x-img src="media/sky-dining.webp" alt="Sky dining terrace at sunset, a long table set among planting with the city beyond." sizes="(min-width: 768px) 50vw, 100vw" class="absolute inset-0 h-full w-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div>
                <figcaption class="absolute bottom-0 left-0 p-6 text-white md:p-10">
                    <p class="site-kicker text-white/70">Dine</p>
                    <p class="mt-3 font-serif text-4xl">Panoramic sky dining</p>
                </figcaption>
            </figure>
            <figure class="media-zoom relative min-h-[70vh]">
                <x-img src="media/sky-lounge.webp" alt="Sky lounge garden at night, with winding paths, seating, and city lights beyond the glass." sizes="(min-width: 768px) 50vw, 100vw" class="absolute inset-0 h-full w-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div>
                <figcaption class="absolute bottom-0 left-0 p-6 text-white md:p-10">
                    <p class="site-kicker text-white/70">Relax</p>
                    <p class="mt-3 font-serif text-4xl">Sky gardens</p>
                </figcaption>
            </figure>
        </div>

        <div class="mx-auto grid max-w-[1600px] gap-10 px-5 py-20 md:grid-cols-3 md:px-10 md:py-28">
            <div class="border-t border-limestone pt-6">
                <p class="site-kicker text-stone">Train</p>
                <h3 class="mt-4 font-serif text-3xl">Fitness centre</h3>
                <p class="mt-3 text-sm leading-relaxed text-stone">An approximately 600 m² fully equipped studio within the development.</p>
            </div>
            <div class="border-t border-limestone pt-6">
                <p class="site-kicker text-stone">Connect</p>
                <h3 class="mt-4 font-serif text-3xl">Social rooms</h3>
                <p class="mt-3 text-sm leading-relaxed text-stone">Shared spaces for gathering, recreation, and the ordinary rhythm of the week.</p>
            </div>
            <div class="border-t border-limestone pt-6">
                <p class="site-kicker text-stone">Entertain</p>
                <h3 class="mt-4 font-serif text-3xl">Private cinema</h3>
                <p class="mt-3 text-sm leading-relaxed text-stone">A private cinema and resident facilities for evenings kept inside the house.</p>
            </div>
        </div>
    </section>

    <section class="bg-dusk text-paper">
        <div class="mx-auto grid max-w-[1600px] items-start gap-16 px-5 py-24 md:px-10 md:py-32 lg:grid-cols-12">
            <div class="lg:col-span-5" data-reveal>
                <p class="site-kicker text-sand">Ownership</p>
                <h2 class="site-display mt-6 text-5xl md:text-6xl">A premium address. A diverse residential product.</h2>
                <p class="mt-6 max-w-md leading-relaxed text-sand">Santorini is designed to appeal to both homeowners and property investors.</p>
                <div class="mt-10 flex flex-wrap gap-3">
                    <a href="{{ route('enquire', ['interest' => 'price-list']) }}" class="site-button site-button-on-dark">Request the price list</a>
                    <a href="{{ route('enquire', ['interest' => 'consultation']) }}" class="link-line self-center text-[0.7rem] tracking-[0.18em] uppercase">Private consultation</a>
                </div>
            </div>
            <ul class="space-y-0 lg:col-span-6 lg:col-start-7">
                @foreach ([
                    ['Prime urban location', 'Lantana Road, within one of Nairobi’s established commercial and lifestyle districts.'],
                    ['A diverse collection', 'Multiple formats for different budgets and ownership goals.'],
                    ['Lifestyle amenities', 'Wellness, recreation, dining, and social facilities.'],
                    ['A recognisable architecture', 'An identity that supports the address over time.'],
                    ['Integrated convenience', 'On-site retail and lifestyle facilities.'],
                    ['Long-term ownership', 'Designed for owner-occupiers and investors.'],
                ] as $point)
                    <li class="border-t border-white/15 py-6">
                        <p class="font-serif text-2xl">{{ $point[0] }}</p>
                        <p class="mt-2 text-sm leading-relaxed text-sand">{{ $point[1] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section id="location" class="mx-auto grid max-w-[1600px] gap-12 px-5 py-24 md:px-10 md:py-32 lg:grid-cols-12">
        <div class="lg:col-span-5" data-reveal>
            <p class="site-kicker text-stone">Location</p>
            <h2 class="site-display mt-6 text-5xl md:text-6xl">Lantana Road, Westlands.</h2>
            <p class="mt-6 leading-relaxed text-ink/80">Santorini sits on Lantana Road in Westlands, combining residential convenience with access to Nairobi’s established business, hospitality, retail, and lifestyle destinations.</p>
            <p class="mt-8 text-sm leading-loose tracking-wide text-stone">Rhapta Road · Riverside · Waiyaki Way · Nairobi CBD · Parklands · Kilimani · Kileleshwa</p>
        </div>
        <div class="lg:col-span-7">
            <iframe title="Map showing Lantana Road, Westlands, Nairobi, Kenya" class="h-[420px] w-full grayscale" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin" src="{{ config('location.embed_url') }}"></iframe>
            <a href="{{ config('location.map_url') }}" class="link-line mt-4 inline-block text-[0.7rem] tracking-[0.18em] uppercase" target="_blank" rel="noopener noreferrer">Open the map</a>
        </div>
    </section>

    <section class="border-t border-limestone">
        <div class="mx-auto grid max-w-[1600px] items-center gap-12 px-5 py-24 md:px-10 lg:grid-cols-12">
            <x-img src="media/logo-love-homes.jpg" alt="LOVE HOMES monogram" sizes="12rem" :fallback="400" class="w-48 lg:col-span-4" />
            <div class="lg:col-span-7 lg:col-start-6" data-reveal>
                <p class="site-kicker text-stone">LOVE HOMES</p>
                <h2 class="site-display mt-6 text-5xl md:text-6xl">Building homes with love. Creating a better life.</h2>
                <p class="mt-6 max-w-xl leading-relaxed text-ink/80">Santorini is the first landmark development under LOVE HOMES, the residential brand of Olmaa Lands Limited. A home should be a place to live, connect, relax, grow, and build a future. This is the first expression of that philosophy in Nairobi.</p>
                <a href="{{ route('about') }}" class="link-line mt-8 inline-block text-[0.7rem] tracking-[0.18em] uppercase">The house behind Santorini</a>
            </div>
        </div>
    </section>

    <section class="relative min-h-[70vh] bg-ink text-white">
        <x-img src="media/arrival.webp" alt="Evening arrival court at Santorini Residences, with the illuminated canopy over the entrance." class="absolute inset-0 h-full w-full object-cover" />
        <div class="absolute inset-0 bg-ink/55"></div>
        <div class="relative flex min-h-[70vh] flex-col items-start justify-end px-5 py-20 md:px-10" data-reveal>
            <h2 class="site-display max-w-3xl text-5xl md:text-7xl">A private introduction.</h2>
            <p class="mt-6 max-w-lg text-white/80">Viewings, the price list, and the investment pack are shared directly.</p>
            <a href="{{ route('enquire') }}" class="site-button site-button-on-dark mt-10">Enquire privately</a>
        </div>
    </section>
@endsection
