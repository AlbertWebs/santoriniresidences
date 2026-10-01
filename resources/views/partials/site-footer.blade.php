@php
    $brand = cms('settings.brand');
    $contact = cms('settings.contact');
    $socialLinks = cms('settings.social');
    $publicTestimonials = \App\Support\TestimonialContent::published();
    $whatsapp = preg_replace('/\D+/', '', (string) $contact['whatsapp']);
    $socialIcons = [
        'instagram' => ['Instagram', '<rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.5" fill="none"/><circle cx="12" cy="12" r="4.2" stroke="currentColor" stroke-width="1.5" fill="none"/><circle cx="17.4" cy="6.6" r="1.1" fill="currentColor"/>'],
        'facebook' => ['Facebook', '<path fill="currentColor" d="M13.5 21v-7.6h2.6l.4-3h-3V8.5c0-.9.25-1.5 1.5-1.5h1.6V4.3c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2.2H7.8v3h2.6V21z"/>'],
        'youtube' => ['YouTube', '<path fill="currentColor" fill-rule="evenodd" d="M21.6 7.7a2.5 2.5 0 0 0-1.77-1.77C18.27 5.5 12 5.5 12 5.5s-6.27 0-7.83.43A2.5 2.5 0 0 0 2.4 7.7 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.3 2.5 2.5 0 0 0 1.77 1.77c1.56.43 7.83.43 7.83.43s6.27 0 7.83-.43a2.5 2.5 0 0 0 1.77-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.3zM10 14.9V9.1l5 2.9z"/>'],
        'tiktok' => ['TikTok', '<path fill="currentColor" d="M16.3 5.9A4 4 0 0 1 15.3 3.3h-2.9v11.6a2.43 2.43 0 0 1-2.43 2.34 2.44 2.44 0 0 1-2.44-2.43c0-1.61 1.56-2.82 3.16-2.33V9.53c-3.23-.43-6.06 2.08-6.06 5.29 0 3.12 2.59 5.34 5.33 5.34 2.94 0 5.33-2.39 5.33-5.34V8.92a6.9 6.9 0 0 0 4.03 1.29V7.32s-1.76.08-3.04-1.42z"/>'],
    ];
@endphp
<footer class="site-footer">
    <div class="mx-auto grid max-w-[1600px] gap-16 px-5 pb-16 pt-20 md:px-10 lg:grid-cols-12 lg:pb-20 lg:pt-28">
        <div class="lg:col-span-5">
            <x-img src="media/logo-santorini-enhanced.png" alt="Santorini Residences" sizes="14rem" :fallback="800" class="brand-mark-footer" />
            <p class="mt-8 max-w-sm font-serif text-2xl leading-snug text-pearl">{{ $brand['tagline'] }}</p>
            <p class="mt-4 max-w-sm text-sm leading-relaxed text-silver/65">{{ $brand['footer_blurb'] }}</p>

            @if ($contact['email'] || $contact['phone'] || $contact['phone_secondary'])
                <ul class="mt-8 space-y-2 text-sm">
                    @if ($contact['email'])
                        <li><a class="footer-link link-line" href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></li>
                    @endif
                    @foreach (array_filter([$contact['phone'], $contact['phone_secondary']]) as $phone)
                        <li><a class="footer-link link-line" href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a></li>
                    @endforeach
                </ul>
            @endif

            <ul class="mt-10 flex flex-wrap gap-3" aria-label="Santorini Residences on social media">
                @foreach ($socialIcons as $key => [$name, $svg])
                    @php($socialUrl = $socialLinks[$key] ?? '')
                    @continue(! $socialUrl)
                    <li>
                        <a href="{{ $socialUrl }}" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="Santorini Residences on {{ $name }}" title="{{ $name }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true">{!! $svg !!}</svg>
                        </a>
                    </li>
                @endforeach
                @if ($whatsapp)
                    <li>
                        <a href="https://wa.me/{{ $whatsapp }}?text={{ rawurlencode($contact['whatsapp_message']) }}" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="Message Santorini Residences on WhatsApp" title="WhatsApp">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12.04 2.5a9.46 9.46 0 0 0-8.1 14.33L2.5 21.5l4.8-1.4a9.46 9.46 0 1 0 4.74-17.6zm0 17.2a7.74 7.74 0 0 1-3.95-1.08l-.28-.17-2.85.83.85-2.77-.19-.29a7.74 7.74 0 1 1 6.42 3.48zm4.25-5.8c-.23-.12-1.37-.68-1.58-.75-.21-.08-.37-.12-.52.11-.15.23-.6.75-.73.9-.14.16-.27.17-.5.06a6.33 6.33 0 0 1-3.13-2.73c-.24-.41.24-.38.68-1.26.07-.15.04-.28-.02-.4-.06-.11-.52-1.25-.71-1.71-.19-.45-.38-.39-.52-.4h-.45a.86.86 0 0 0-.62.29 2.6 2.6 0 0 0-.81 1.93 4.5 4.5 0 0 0 .95 2.4 10.3 10.3 0 0 0 3.95 3.49c1.47.63 2.04.69 2.78.58.45-.07 1.37-.56 1.56-1.1.2-.54.2-1 .14-1.1-.06-.1-.21-.16-.44-.27z"/></svg>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="grid gap-12 sm:grid-cols-2 lg:col-span-7 lg:grid-cols-3 lg:gap-10">
            <div>
                <p class="site-kicker footer-title">Visit</p>
                <ul class="mt-7 space-y-3.5 text-sm">
                    <li><a class="footer-link link-line" href="{{ route('residences') }}">Residences</a></li>
                    <li><a class="footer-link link-line" href="{{ route('gallery') }}">Gallery</a></li>
                    <li><a class="footer-link link-line" href="{{ route('about') }}">LOVE HOMES</a></li>
                    <li><a class="footer-link link-line" href="{{ route('insights') }}">Insights</a></li>
                    <li><a class="footer-link link-line" href="{{ route('privacy') }}">Privacy policy</a></li>
                    @if ($publicTestimonials)<li><a class="footer-link link-line" href="{{ route('testimonials') }}">Testimonials</a></li>@endif
                    <li><a class="footer-link link-line" href="{{ route('home') }}#location">Location</a></li>
                </ul>
            </div>
            <div>
                <p class="site-kicker footer-title">Enquire</p>
                <ul class="mt-7 space-y-3.5 text-sm">
                    <li><a class="footer-link link-line" href="{{ route('visit.book') }}">Book a site visit</a></li>
                    <li><a class="footer-link link-line" href="{{ route('enquire', ['interest' => 'private-viewing']) }}">Private viewing</a></li>
                    <li><a class="footer-link link-line" href="{{ route('enquire', ['interest' => 'price-list']) }}">Price list</a></li>
                    <li><a class="footer-link link-line" href="{{ route('enquire', ['interest' => 'investment-pack']) }}">Investment pack</a></li>
                    <li><a class="footer-link link-line" href="{{ route('enquire', ['interest' => 'consultation']) }}">Private consultation</a></li>
                </ul>
            </div>
            <div>
                <p class="site-kicker footer-title">The house</p>
                <div class="mt-7 space-y-5 text-sm leading-relaxed text-silver/65">
                    @foreach (cms('settings.footer.house', []) as $entity)
                        <p><span class="text-pearl">{{ $entity['name'] }}</span>@if ($entity['line'])<br>{{ $entity['line'] }}@endif</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-[1600px] px-5 md:px-10">
        <div class="flex flex-col gap-4 border-t border-champagne/20 py-7 text-xs tracking-[0.08em] text-silver/55 md:flex-row md:items-center md:justify-between">
            <p>&copy; {{ date('Y') }} {{ $brand['copyright'] }}</p>
            <p>{{ $brand['address'] }}</p>
            <a href="#top" class="footer-link link-line w-fit uppercase tracking-[0.22em]" @click.prevent="window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">Back to top</a>
        </div>
    </div>
</footer>
