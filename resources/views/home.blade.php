<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Santorini Residences | Luxury Off-Plan Properties</title>
    <meta name="description" content="Discover premium off-plan residences with transparent payment plans, VR tours, and white-glove advisory.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-50 text-neutral-900" x-data="{ mobileNav: false }">
    {{-- Navigation --}}
    <header class="fixed inset-x-0 top-0 z-50 border-b border-white/20 bg-white/75 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-900 text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M4 20V9l8-5 8 5v11h-5v-6H9v6H4Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <span>
                    <span class="block text-sm font-semibold tracking-wide">Santorini Residences</span>
                    <span class="block text-xs text-neutral-500">Off-Plan Collection</span>
                </span>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-medium text-neutral-600 lg:flex">
                <a href="#projects" class="transition hover:text-neutral-900">Projects</a>
                <a href="#services" class="transition hover:text-neutral-900">Services</a>
                <a href="#process" class="transition hover:text-neutral-900">How It Works</a>
                <a href="#contact" class="transition hover:text-neutral-900">Contact</a>
            </nav>

            <div class="hidden items-center gap-3 lg:flex">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-neutral-600 transition hover:text-neutral-900">Admin</a>
                <a href="#contact" class="btn-primary">Book a Site Visit</a>
            </div>

            <button class="rounded-lg border border-neutral-200 p-2 lg:hidden" @click="mobileNav = !mobileNav">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>

        <div x-show="mobileNav" x-transition class="border-t border-neutral-200 bg-white px-4 py-4 lg:hidden" style="display: none;">
            <nav class="flex flex-col gap-3 text-sm font-medium text-neutral-700">
                <a href="#projects" @click="mobileNav = false">Projects</a>
                <a href="#services" @click="mobileNav = false">Services</a>
                <a href="#process" @click="mobileNav = false">How It Works</a>
                <a href="#contact" @click="mobileNav = false">Contact</a>
                <a href="{{ route('admin.dashboard') }}" class="text-neutral-500">Admin</a>
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-28 pb-20 lg:pt-36 lg:pb-28">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(185,151,91,0.15),transparent_50%),radial-gradient(circle_at_bottom_left,rgba(23,28,39,0.08),transparent_55%)]"></div>
        <div class="relative mx-auto grid max-w-7xl gap-12 px-4 lg:grid-cols-2 lg:items-center lg:px-8">
            <div>
                <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-neutral-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-wider text-neutral-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-accent-500"></span>
                    Premium Off-Plan Living
                </p>
                <h1 class="text-4xl font-semibold leading-tight tracking-tight text-neutral-900 lg:text-6xl">
                    Invest in iconic residences before they rise.
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-neutral-600">
                    Curated off-plan developments with milestone-based payments, immersive VR tours, and end-to-end settlement support for discerning buyers.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#projects" class="btn-primary">Explore Projects</a>
                    <a href="#contact" class="btn-secondary">Download Brochure</a>
                </div>
                <div class="mt-10 grid grid-cols-3 gap-4 border-t border-neutral-200 pt-8">
                    <div>
                        <p class="text-2xl font-semibold text-neutral-900">18+</p>
                        <p class="text-xs text-neutral-500">Active Developments</p>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-neutral-900">$48M</p>
                        <p class="text-xs text-neutral-500">Settled Volume</p>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-neutral-900">326</p>
                        <p class="text-xs text-neutral-500">Active Leads</p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="aspect-[4/5] overflow-hidden rounded-3xl border border-neutral-200 bg-neutral-900 shadow-2xl">
                    <div class="absolute inset-0 bg-[linear-gradient(160deg,rgba(255,255,255,0.08),transparent_40%),linear-gradient(to_top,rgba(0,0,0,0.75),transparent_55%)]"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-8 text-white">
                        <p class="text-xs uppercase tracking-wider text-neutral-300">Featured Project</p>
                        <h2 class="mt-2 text-2xl font-semibold">Aegean Crown Villas</h2>
                        <p class="mt-2 text-sm text-neutral-300">North Coast · From $320,000 · Q2 2027 Handover</p>
                        <div class="mt-4 inline-flex w-fit rounded-full bg-amber-400/20 px-3 py-1 text-xs font-medium text-amber-200">Selling Fast</div>
                    </div>
                </div>
                <div class="absolute -bottom-6 -left-6 hidden rounded-2xl border border-neutral-200 bg-white p-4 shadow-lg lg:block">
                    <p class="text-xs text-neutral-500">Virtual Tour Available</p>
                    <p class="text-sm font-semibold text-neutral-900">360° VR Walkthrough</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Projects --}}
    <section id="projects" class="border-t border-neutral-200 bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="mb-12 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Featured Developments</p>
                    <h2 class="mt-2 text-3xl font-semibold text-neutral-900">Off-plan projects now accepting reservations</h2>
                </div>
                <a href="#contact" class="btn-secondary">Request Full Portfolio</a>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    ['name' => 'Aegean Crown Villas', 'location' => 'North Coast', 'price' => 'From $320,000', 'status' => 'Selling Fast', 'badge' => 'bg-amber-100 text-amber-800', 'handover' => 'Q2 2027'],
                    ['name' => 'Marina Heights', 'location' => 'West Bay', 'price' => 'From $410,000', 'status' => 'Pre-Launch', 'badge' => 'bg-blue-100 text-blue-700', 'handover' => 'Q4 2028'],
                    ['name' => 'Azure Dunes Residences', 'location' => 'Palm District', 'price' => 'From $540,000', 'status' => 'Sold Out', 'badge' => 'bg-emerald-100 text-emerald-700', 'handover' => 'Q1 2026'],
                ] as $project)
                    <article class="group overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 transition hover:border-neutral-300 hover:shadow-md">
                        <div class="aspect-[4/3] bg-gradient-to-br from-neutral-800 to-neutral-600"></div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-lg font-semibold text-neutral-900">{{ $project['name'] }}</h3>
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $project['badge'] }}">{{ $project['status'] }}</span>
                            </div>
                            <p class="mt-1 text-sm text-neutral-500">{{ $project['location'] }}</p>
                            <div class="mt-4 flex items-center justify-between text-sm">
                                <span class="font-medium text-neutral-900">{{ $project['price'] }}</span>
                                <span class="text-neutral-500">{{ $project['handover'] }}</span>
                            </div>
                            <a href="#contact" class="mt-4 inline-flex text-sm font-medium text-neutral-700 transition group-hover:text-neutral-900">
                                View details &rarr;
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section id="services" class="py-20">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="mb-12 max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Advisory Services</p>
                <h2 class="mt-2 text-3xl font-semibold text-neutral-900">White-glove support from reservation to handover</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['title' => 'Investment Consulting', 'desc' => 'Market analysis, yield modeling, and project shortlisting tailored to your goals.'],
                    ['title' => 'Architectural Advisory', 'desc' => 'Finish selections, layout reviews, and premium customization guidance.'],
                    ['title' => 'Payment Structuring', 'desc' => 'Transparent milestone plans with escrow-backed staging payments.'],
                    ['title' => 'Property Management', 'desc' => 'Tenant placement, maintenance coordination, and asset reporting.'],
                ] as $service)
                    <div class="rounded-2xl border border-neutral-200 bg-white p-5 transition hover:border-neutral-300 hover:shadow-sm">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-100 text-neutral-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M4 12h16M12 4v16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </div>
                        <h3 class="text-base font-semibold text-neutral-900">{{ $service['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-neutral-600">{{ $service['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Process --}}
    <section id="process" class="border-y border-neutral-200 bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="mb-12 text-center">
                <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">How Off-Plan Works</p>
                <h2 class="mt-2 text-3xl font-semibold text-neutral-900">A clear path from selection to settlement</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['step' => '01', 'title' => 'Discover', 'desc' => 'Browse developments, VR tours, and unit availability.'],
                    ['step' => '02', 'title' => 'Reserve', 'desc' => 'Secure your unit with a reservation deposit and offer letter.'],
                    ['step' => '03', 'title' => 'Stage Payments', 'desc' => 'Follow construction-linked milestones through regulated escrow.'],
                    ['step' => '04', 'title' => 'Handover', 'desc' => 'Complete final settlement and receive your premium residence.'],
                ] as $item)
                    <div class="rounded-2xl border border-neutral-200 p-5">
                        <p class="text-xs font-semibold text-accent-500">{{ $item['step'] }}</p>
                        <h3 class="mt-2 text-lg font-semibold text-neutral-900">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm text-neutral-600">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="py-20">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="mb-12">
                <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Client Stories</p>
                <h2 class="mt-2 text-3xl font-semibold text-neutral-900">Trusted by international investors</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <blockquote class="rounded-2xl border border-neutral-200 bg-white p-6">
                    <p class="text-sm leading-relaxed text-neutral-700">"The team guided us through every milestone with complete transparency. Our Aegean Crown villa exceeded expectations."</p>
                    <footer class="mt-4 text-sm font-semibold text-neutral-900">Sarah & Michael Chen</footer>
                    <p class="text-xs text-neutral-500">Off-plan buyer · Aegean Crown Villas</p>
                </blockquote>
                <blockquote class="rounded-2xl border border-neutral-200 bg-white p-6">
                    <p class="text-sm leading-relaxed text-neutral-700">"From VR tours to escrow documentation, the process felt premium and secure at every step."</p>
                    <footer class="mt-4 text-sm font-semibold text-neutral-900">David Okoro</footer>
                    <p class="text-xs text-neutral-500">Investor · Marina Heights</p>
                </blockquote>
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section id="contact" class="border-t border-neutral-200 bg-neutral-900 py-20 text-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 lg:grid-cols-2 lg:px-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Get in Touch</p>
                <h2 class="mt-2 text-3xl font-semibold">Book a private site visit or virtual walkthrough</h2>
                <p class="mt-4 text-neutral-300">Speak with our advisory team about availability, payment plans, and handover timelines.</p>
            </div>
            <form class="space-y-4 rounded-2xl border border-neutral-700 bg-neutral-800/50 p-6" x-data="{ sent: false }" @submit.prevent="sent = true">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-neutral-300">Full Name</label>
                        <input type="text" class="admin-input" placeholder="Your name" required>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-neutral-300">Email</label>
                        <input type="email" class="admin-input" placeholder="you@email.com" required>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-neutral-300">Interested Project</label>
                    <select class="admin-input">
                        <option>Aegean Crown Villas</option>
                        <option>Marina Heights</option>
                        <option>Azure Dunes Residences</option>
                        <option>General Inquiry</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-neutral-300">Message</label>
                    <textarea rows="4" class="admin-input" placeholder="Tell us about your investment goals..."></textarea>
                </div>
                <button type="submit" class="btn-primary w-full">Submit Inquiry</button>
                <p x-show="sent" x-transition class="text-center text-sm text-emerald-400" style="display: none;">
                    Thank you — our team will contact you within 24 hours.
                </p>
            </form>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-neutral-200 bg-white py-10">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 text-sm text-neutral-500 md:flex-row lg:px-8">
            <p>&copy; {{ date('Y') }} Santorini Residences. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#projects" class="transition hover:text-neutral-900">Projects</a>
                <a href="#services" class="transition hover:text-neutral-900">Services</a>
                <a href="{{ route('admin.dashboard') }}" class="transition hover:text-neutral-900">Admin</a>
            </div>
        </div>
    </footer>
</body>
</html>
