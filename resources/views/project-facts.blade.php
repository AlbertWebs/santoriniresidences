@extends('layouts.site')

@php
    $projectUrl = \App\Support\Seo::url();
    $faqItems = [
        [
            'question' => 'What is Santorini Residences?',
            'answer' => 'Santorini Residences is a residential development by Olmaa Lands Limited under the LOVE HOMES brand. The project comprises 328 homes on Lantana Road in Westlands, Nairobi.',
        ],
        [
            'question' => 'Where is Santorini Residences located?',
            'answer' => 'Santorini Residences is located on Lantana Road, in Westlands, Nairobi, Kenya. Use the map link on the official website to view the location.',
        ],
        [
            'question' => 'What types of homes are available at Santorini Residences?',
            'answer' => 'The project includes one-bedroom, two-bedroom and three-bedroom residences, as well as loft residences. Plans and specifications are listed on the Residences page.',
        ],
        [
            'question' => 'How many homes are in Santorini Residences?',
            'answer' => 'Santorini Residences has 328 residences across its twin-tower development.',
        ],
        [
            'question' => 'Who is the developer of Santorini Residences?',
            'answer' => 'Santorini Residences is a development of Olmaa Lands Limited and the flagship project of LOVE HOMES. Olmaa Lands Limited is backed by Jiangsu Hetian Construction Co., Ltd. (江苏禾田建设有限公司).',
        ],
        [
            'question' => 'How can I get current prices, availability or arrange a viewing?',
            'answer' => 'Availability and prices may change. Contact the Santorini Residences team through the official Enquire page to request current information or book a site visit.',
        ],
    ];
@endphp

@section('title', 'Santorini Residences Project Facts & FAQs | Westlands, Nairobi')
@section('description', 'Official facts about Santorini Residences: location, developer, home types, project size and how to request current prices or arrange a site visit in Westlands, Nairobi.')

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    '@id' => $projectUrl.'/project-facts#faqs',
    'url' => $projectUrl.'/project-facts',
    'name' => 'Santorini Residences Project Facts and FAQs',
    'isPartOf' => ['@id' => $projectUrl.'#website'],
    'about' => ['@id' => $projectUrl.'#residences'],
    'mainEntity' => array_map(fn ($item) => [
        '@type' => 'Question',
        'name' => $item['question'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
    ], $faqItems),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    <section class="bg-ink text-pearl">
        <div class="mx-auto max-w-5xl px-5 pb-20 pt-36 md:px-10 md:pb-28 md:pt-48">
            <p class="site-kicker text-champagne">Official project information</p>
            <h1 class="site-display mt-6 max-w-4xl text-5xl md:text-7xl">Santorini Residences, Westlands Nairobi</h1>
            <p class="mt-8 max-w-3xl font-serif text-2xl leading-relaxed text-pearl/85 md:text-3xl">Santorini Residences is a 328-home development on Lantana Road, Westlands, by Olmaa Lands Limited under the LOVE HOMES brand.</p>
            <p class="mt-6 max-w-2xl text-base leading-relaxed text-pearl/70">This is the official project website. Explore residence plans, project details and the location, or contact the team for current pricing and availability.</p>
            <div class="mt-8 flex flex-wrap gap-6">
                <a class="link-line" href="{{ route('residences') }}">Explore residences <span aria-hidden="true">→</span></a>
                <a class="link-line" href="{{ route('enquire') }}">Request current information <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-5 py-20 md:px-10 md:py-28" aria-labelledby="facts-title">
        <p class="site-kicker text-navy">At a glance</p>
        <h2 id="facts-title" class="site-display mt-5 text-4xl text-ink md:text-5xl">Santorini Residences project facts</h2>
        <dl class="mt-10 grid gap-6 border-t border-navy pt-8 sm:grid-cols-2">
            <div><dt class="site-kicker text-stone">Project</dt><dd class="mt-2 font-serif text-2xl text-ink">Santorini Residences</dd></div>
            <div><dt class="site-kicker text-stone">Address</dt><dd class="mt-2 font-serif text-2xl text-ink">Lantana Road, Westlands, Nairobi, Kenya</dd></div>
            <div><dt class="site-kicker text-stone">Homes</dt><dd class="mt-2 font-serif text-2xl text-ink">328 residences</dd></div>
            <div><dt class="site-kicker text-stone">Home types</dt><dd class="mt-2 font-serif text-2xl text-ink">1, 2 and 3-bedroom homes, plus loft residences</dd></div>
            <div><dt class="site-kicker text-stone">Developer</dt><dd class="mt-2 font-serif text-2xl text-ink">Olmaa Lands Limited</dd></div>
            <div><dt class="site-kicker text-stone">Residential brand</dt><dd class="mt-2 font-serif text-2xl text-ink">LOVE HOMES</dd></div>
        </dl>
    </section>

    <section class="bg-ivory" aria-labelledby="faq-title">
        <div class="mx-auto max-w-5xl px-5 py-20 md:px-10 md:py-28">
            <p class="site-kicker text-navy">Clear answers</p>
            <h2 id="faq-title" class="site-display mt-5 text-4xl text-ink md:text-5xl">Frequently asked questions</h2>
            <dl class="mt-10 border-t border-navy">
                @foreach ($faqItems as $item)
                    <div class="border-b border-navy/20 py-7">
                        <dt class="font-serif text-2xl text-ink">{{ $item['question'] }}</dt>
                        <dd class="mt-3 max-w-3xl text-base leading-relaxed text-ink/70">{{ $item['answer'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>
@endsection
