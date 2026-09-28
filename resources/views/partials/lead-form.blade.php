@php
    /** @var \App\Models\LeadForm $form */
    $fields = $form->activeFields();
    $preset = $preset ?? [];
    $funnel = $funnel ?? null;
    $groups = array_filter([
        'Your details' => array_intersect_key($fields, array_flip(['name', 'email', 'phone'])),
        'Your visit' => array_intersect_key($fields, array_flip(['preferred_date', 'preferred_time', 'guests', 'contact_channel'])),
        'Your interest' => array_intersect_key($fields, array_flip(['interest', 'residence_type'])),
        'Your message' => array_intersect_key($fields, array_flip(['message'])),
    ]);
    $successWords = explode(' ', trim($form->success_title ?: 'Thank you.'));
    $successAccent = count($successWords) > 1 ? array_pop($successWords) : null;
@endphp

<div id="lead-{{ $form->key }}" class="scroll-mt-32">
    @if (session('lead_submitted') === $form->key)
        <div class="max-w-xl border-t border-navy pt-12" role="status">
            <div class="flex items-center gap-4">
                @include('partials.wave-mark-navy')
                <p class="site-kicker text-navy">Received</p>
            </div>
            <p class="site-display mt-10 text-5xl text-ink md:text-6xl">{{ implode(' ', $successWords) }} @if ($successAccent)<span class="accent-navy">{{ $successAccent }}</span>@endif</p>
            <p class="mt-8 max-w-md text-[1.05rem] leading-[1.85] text-ink/72">{{ $form->success_message }}</p>
            <a href="{{ route('home') }}" class="link-navy mt-12">Return to Santorini</a>
        </div>
    @else
        <form method="POST" action="{{ $form->key === 'general' ? route('enquire.store') : route('leads.store', $form) }}" class="relative max-w-xl" novalidate>
            @csrf
            <input type="hidden" name="page" value="{{ '/'.ltrim(request()->path(), '/') }}">
            @if ($funnel)
                <input type="hidden" name="funnel" value="{{ $funnel->slug }}">
            @endif
            @foreach (['utm_source', 'utm_medium', 'utm_campaign'] as $utm)
                @if (request()->filled($utm))
                    <input type="hidden" name="{{ $utm }}" value="{{ \Illuminate\Support\Str::limit((string) request($utm), 120, '') }}">
                @endif
            @endforeach
            <div class="absolute -left-[9999px]" aria-hidden="true">
                <label for="{{ $form->key }}-company">Company</label>
                <input id="{{ $form->key }}-company" name="company" type="text" tabindex="-1" autocomplete="off">
            </div>

            @foreach ($groups as $legend => $groupFields)
                <fieldset @class(['border-t border-navy pt-8', 'mt-16' => ! $loop->first])>
                    <legend class="float-left flex w-full items-center gap-4">
                        <span class="font-serif text-[1.05rem] italic tracking-[0.04em] text-navy/55">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="site-kicker text-navy">{{ $legend }}</span>
                    </legend>
                    <div class="clear-both grid gap-8 pt-6 md:grid-cols-2">
                        @foreach ($groupFields as $name => $field)
                            @php
                                $id = $form->key.'-'.$name;
                                $value = old($name, $preset[$name] ?? '');
                                $wide = in_array($field['input'], ['choice', 'textarea'], true) || $name === 'name';
                            @endphp
                            <div @class(['md:col-span-2' => $wide])>
                                @if ($field['input'] === 'choice')
                                    <p id="{{ $id }}-label" class="site-kicker text-stone">{{ $field['label'] }}@unless ($field['required']) <span class="normal-case tracking-normal text-stone/80">(optional)</span>@endunless</p>
                                    <div class="mt-4 grid gap-3 {{ count($field['options']) > 4 ? 'sm:grid-cols-2' : 'grid-cols-2' }}" role="radiogroup" aria-labelledby="{{ $id }}-label">
                                        @foreach ($field['options'] as $optionValue => $optionLabel)
                                            <label class="choice">
                                                <input type="radio" name="{{ $name }}" value="{{ $optionValue }}" class="choice__input sr-only" @checked((string) $value === (string) $optionValue) @required($field['required'])>
                                                <span class="choice__box">{{ $optionLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif ($field['input'] === 'textarea')
                                    <label for="{{ $id }}" class="site-kicker text-stone">{{ $field['label'] }}@unless ($field['required']) <span class="normal-case tracking-normal text-stone/80">(optional)</span>@endunless</label>
                                    <textarea id="{{ $id }}" name="{{ $name }}" rows="4" class="site-field mt-2 resize-y" @required($field['required'])>{{ $value }}</textarea>
                                @else
                                    <label for="{{ $id }}" class="site-kicker text-stone">{{ $field['label'] }}@unless ($field['required']) <span class="normal-case tracking-normal text-stone/80">(optional)</span>@endunless</label>
                                    <input
                                        id="{{ $id }}"
                                        name="{{ $name }}"
                                        type="{{ $field['input'] }}"
                                        value="{{ $value }}"
                                        class="site-field mt-2"
                                        @if (! empty($field['autocomplete'])) autocomplete="{{ $field['autocomplete'] }}" @endif
                                        @if ($field['input'] === 'date') min="{{ now()->toDateString() }}" @endif
                                        @if ($field['input'] === 'number') min="{{ $field['min'] ?? 1 }}" max="{{ $field['max'] ?? 10 }}" inputmode="numeric" @endif
                                        @required($field['required'])
                                    >
                                @endif
                                @error($name)<p class="mt-2 text-sm text-dusk">{{ $message }}</p>@enderror
                            </div>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach

            <button type="submit" class="site-button site-button-navy mt-14">
                {{ $form->button_label ?: 'Send' }}
                <svg class="h-3 w-6" viewBox="0 0 28 12" fill="none" aria-hidden="true"><path d="M0 6h26M21 1l5 5-5 5" stroke="currentColor" stroke-width="1"/></svg>
            </button>
        </form>
    @endif
</div>
