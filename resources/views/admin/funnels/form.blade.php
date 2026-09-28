@php($editing = $funnel->exists)
<x-layouts.admin :title="$editing ? $funnel->name : 'New funnel'" kicker="Social funnel">
    <x-slot:actions>
        <a href="{{ route('admin.funnels.index') }}" class="adm-btn adm-btn--ghost adm-btn--sm hidden md:inline-flex">All funnels</a>
    </x-slot:actions>

    <form method="POST" action="{{ $editing ? route('admin.funnels.update', $funnel) : route('admin.funnels.store') }}"
        x-data="pageEditor()"
        x-ref="form" @input="markDirty()" @change="markDirty()" @cms-dirty="markDirty()" @submit="saving = true"
        class="grid gap-8 xl:grid-cols-[1fr_24rem]">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="contents" x-data="{
            slug: @js(old('slug', $funnel->slug)),
            name: @js(old('name', $funnel->name)),
            channel: @js(old('channel', $funnel->channel)),
            slugTouched: @js($editing),
            campaign: @js(old('utm_campaign', $funnel->utm_campaign)),
            source: @js(old('utm_source', $funnel->utm_source)),
            medium: @js(old('utm_medium', $funnel->utm_medium)),
            get shareUrl() {
                const query = new URLSearchParams({ utm_source: this.source || this.channel, utm_medium: this.medium || 'social', utm_campaign: this.campaign || this.slug });
                return @js(url('/go')) + '/' + (this.slug || '') + '?' + query.toString();
            },
        }">
        <div class="space-y-6">
            <section class="adm-section is-open">
                <div class="adm-section__toggle cursor-default">
                    <span class="adm-section__index">01</span>
                    <span><span class="adm-title block text-2xl">Link</span><span class="mt-1 block text-xs text-[#6f675e]">Where the link will be shared and which form it opens.</span></span>
                    <span></span>
                </div>
                <div class="adm-section__body grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="name" class="adm-label">Internal name</label>
                        <input id="name" name="name" type="text" x-model="name" @input="if (!slugTouched) slug = name.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '')" class="adm-input" placeholder="For example: Instagram bio, book a visit" required>
                        @error('name')<p class="adm-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="channel" class="adm-label">Channel</label>
                        <select id="channel" name="channel" x-model="channel" class="adm-select">
                            @foreach (\App\Models\Funnel::CHANNELS as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="lead_form_id" class="adm-label">Form</label>
                        <select id="lead_form_id" name="lead_form_id" class="adm-select" required>
                            @foreach ($forms as $form)
                                <option value="{{ $form->id }}" @selected((string) old('lead_form_id', $funnel->lead_form_id) === (string) $form->id)>{{ $form->title }} ({{ \App\Support\LeadFormTypes::label($form->type) }})</option>
                            @endforeach
                        </select>
                        @error('lead_form_id')<p class="adm-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="slug" class="adm-label">Link address</label>
                        <div class="flex items-stretch">
                            <span class="flex items-center border border-r-0 border-[#e6dfd3] bg-[#f4f0e8] px-3 font-mono text-[0.78rem] text-[#6f675e]">{{ url('/go') }}/</span>
                            <input id="slug" name="slug" type="text" x-model="slug" @input="slugTouched = true" class="adm-input font-mono text-[0.82rem]" required>
                        </div>
                        @error('slug')<p class="adm-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <section class="adm-section is-open">
                <div class="adm-section__toggle cursor-default">
                    <span class="adm-section__index">02</span>
                    <span><span class="adm-title block text-2xl">Landing page</span><span class="mt-1 block text-xs text-[#6f675e]">Leave the headline empty to use the form's own title and introduction.</span></span>
                    <span></span>
                </div>
                <div class="adm-section__body grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="headline" class="adm-label">Headline</label>
                        <input id="headline" name="headline" type="text" value="{{ old('headline', $funnel->headline) }}" class="adm-input adm-input--serif" placeholder="Visit Santorini">
                    </div>
                    <div>
                        <label for="headline_accent" class="adm-label">Headline accent</label>
                        <input id="headline_accent" name="headline_accent" type="text" value="{{ old('headline_accent', $funnel->headline_accent) }}" class="adm-input adm-input--serif" placeholder="in person.">
                    </div>
                    <div class="md:col-span-2">
                        <label for="subline" class="adm-label">Introduction</label>
                        <textarea id="subline" name="subline" rows="3" class="adm-textarea">{{ old('subline', $funnel->subline) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <p class="adm-label">Image</p>
                        <div x-data="mediaField(@js(old('media_path', $funnel->media?->path ?? '')), 'image', @js(cms('visit.aside.image')))" class="adm-image-field">
                            <input type="hidden" name="media_path" :value="value">
                            <div class="adm-thumb adm-image-field__preview"><img x-show="preview" :src="preview" alt=""></div>
                            <div class="min-w-0">
                                <p class="truncate font-mono text-[0.75rem] text-[#0e1e37]" x-text="value || fallback"></p>
                                <p class="mt-1 text-xs text-[#6f675e]" x-text="value ? 'Custom image' : 'Site visit page image'"></p>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <button type="button" class="adm-btn adm-btn--sm" @click="choose()">Choose image</button>
                                    <button type="button" class="adm-btn adm-btn--ghost adm-btn--sm" x-show="value" @click="clear()">Use default</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="adm-section is-open">
                <div class="adm-section__toggle cursor-default">
                    <span class="adm-section__index">03</span>
                    <span><span class="adm-title block text-2xl">Campaign tracking</span><span class="mt-1 block text-xs text-[#6f675e]">Optional. Defaults to the channel, "social" and the link address.</span></span>
                    <span></span>
                </div>
                <div class="adm-section__body grid gap-6 md:grid-cols-3">
                    <div>
                        <label for="utm_source" class="adm-label">Source</label>
                        <input id="utm_source" name="utm_source" type="text" x-model="source" class="adm-input" :placeholder="channel">
                    </div>
                    <div>
                        <label for="utm_medium" class="adm-label">Medium</label>
                        <input id="utm_medium" name="utm_medium" type="text" x-model="medium" class="adm-input" placeholder="social">
                    </div>
                    <div>
                        <label for="utm_campaign" class="adm-label">Campaign</label>
                        <input id="utm_campaign" name="utm_campaign" type="text" x-model="campaign" class="adm-input" :placeholder="slug">
                    </div>
                </div>
            </section>

            <div class="adm-savebar">
                <div class="flex items-center gap-3 text-sm">
                    <span class="adm-savebar__dot" x-show="dirty"></span>
                    <span x-show="dirty">You have unsaved changes.</span>
                    <span x-show="!dirty" class="text-[#e8e9ea]/60">{{ $editing ? 'All changes saved.' : 'Fill in the details, then create the funnel.' }}</span>
                </div>
                <button type="submit" class="adm-btn adm-btn--gold" :disabled="saving"><span x-text="saving ? 'Saving' : '{{ $editing ? 'Save funnel' : 'Create funnel' }}'"></span></button>
            </div>
        </div>

        <aside class="space-y-5 xl:sticky xl:top-28 xl:self-start">
            <div class="adm-card space-y-5 p-7">
                <p class="adm-kicker">Share link</p>
                <div>
                    <div class="adm-copy">
                        <input type="text" readonly :value="shareUrl" aria-label="Share link">
                        <button type="button" @click="navigator.clipboard?.writeText(shareUrl).then(() => $store.toasts.push('Link copied to the clipboard.'))">Copy</button>
                    </div>
                    <p class="adm-help">{{ $editing ? 'Paste this link into your profile, story, advert or message.' : 'The link works once the funnel is created.' }}</p>
                </div>
                <label class="flex items-center justify-between gap-4" x-data="{ on: @js((bool) old('is_active', $funnel->is_active)) }">
                    <span>
                        <span class="adm-label mb-0">Live</span>
                        <span class="block text-xs text-[#6f675e]">Paused funnels show a not-found page.</span>
                    </span>
                    <input type="hidden" name="is_active" :value="on ? 1 : 0">
                    <button type="button" class="adm-switch" :class="on && 'is-on'" @click="on = !on; $dispatch('cms-dirty')" role="switch" :aria-checked="on.toString()"></button>
                </label>
                @if ($editing)
                    <a href="{{ $funnel->shareUrl() }}" target="_blank" rel="noopener noreferrer" class="adm-btn adm-btn--ghost w-full">Preview landing page</a>
                @endif
            </div>

            @if ($editing)
                <div class="adm-card p-7">
                    <p class="adm-kicker">Results</p>
                    <div class="mt-4 grid grid-cols-3 gap-4">
                        <div><p class="font-serif text-3xl">{{ number_format($funnel->visits) }}</p><p class="text-xs text-[#6f675e]">Visits</p></div>
                        <div><p class="font-serif text-3xl">{{ number_format($funnel->leads_count) }}</p><p class="text-xs text-[#6f675e]">Leads</p></div>
                        <div><p class="font-serif text-3xl text-[#0e1e37]">{{ $funnel->visits ? number_format($funnel->leads_count / $funnel->visits * 100, 1) : 0 }}%</p><p class="text-xs text-[#6f675e]">Rate</p></div>
                    </div>
                    @if ($recentLeads->isNotEmpty())
                        <p class="adm-label mt-6">Latest leads</p>
                        <ul class="space-y-2">
                            @foreach ($recentLeads as $lead)
                                <li><a href="{{ route('admin.leads.show', $lead) }}" class="flex items-center justify-between gap-3 text-sm hover:text-[#0e1e37]"><span class="truncate">{{ $lead->name }}</span><span class="shrink-0 text-xs text-[#6f675e]">{{ $lead->created_at->diffForHumans(short: true) }}</span></a></li>
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ route('admin.leads.index', ['funnel' => $funnel->id]) }}" class="adm-link mt-6 inline-block">All leads from this funnel</a>
                </div>

                <button type="submit" form="delete-funnel" class="text-xs text-[#9c3b2e] underline-offset-4 hover:underline">Delete this funnel</button>
            @endif
        </aside>
        </div>
    </form>

    @if ($editing)
        <form id="delete-funnel" method="POST" action="{{ route('admin.funnels.destroy', $funnel) }}" onsubmit="return confirm('Delete this funnel? Its link will stop working. Leads are kept.')">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-layouts.admin>
