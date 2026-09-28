<x-layouts.admin :title="$form->title" kicker="Lead form">
    <x-slot:actions>
        <a href="{{ route('admin.forms.index') }}" class="adm-btn adm-btn--ghost adm-btn--sm hidden md:inline-flex">All forms</a>
    </x-slot:actions>

    <form method="POST" action="{{ route('admin.forms.update', $form) }}" x-data="pageEditor()" x-ref="form" @input="markDirty()" @change="markDirty()" @submit="saving = true" class="grid gap-8 xl:grid-cols-[1fr_22rem]">
        @csrf
        @method('PUT')

        <div class="min-w-0 space-y-6">
            <section class="adm-section is-open">
                <div class="adm-section__toggle cursor-default">
                    <span class="adm-section__index">01</span>
                    <span><span class="adm-title block text-2xl">Words</span><span class="mt-1 block text-xs text-[#6f675e]">The title and introduction appear on the form's own page and on funnels without a headline.</span></span>
                    <span></span>
                </div>
                <div class="adm-section__body grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="title" class="adm-label">Title</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $form->title) }}" class="adm-input adm-input--serif" required>
                        @error('title')<p class="adm-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="intro" class="adm-label">Introduction</label>
                        <textarea id="intro" name="intro" rows="3" class="adm-textarea">{{ old('intro', $form->intro) }}</textarea>
                    </div>
                    <div>
                        <label for="button_label" class="adm-label">Button label</label>
                        <input id="button_label" name="button_label" type="text" value="{{ old('button_label', $form->button_label) }}" class="adm-input" required>
                    </div>
                    <div>
                        <label for="success_title" class="adm-label">Thank-you headline</label>
                        <input id="success_title" name="success_title" type="text" value="{{ old('success_title', $form->success_title) }}" class="adm-input" required>
                        <p class="adm-help">The last word is set in italic navy.</p>
                    </div>
                    <div class="md:col-span-2">
                        <label for="success_message" class="adm-label">Thank-you message</label>
                        <textarea id="success_message" name="success_message" rows="3" class="adm-textarea" required>{{ old('success_message', $form->success_message) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="adm-section is-open">
                <div class="adm-section__toggle cursor-default">
                    <span class="adm-section__index">02</span>
                    <span><span class="adm-title block text-2xl">Fields</span><span class="mt-1 block text-xs text-[#6f675e]">Switch fields on or off, mark them required, and rename them. Name and email are always collected.</span></span>
                    <span></span>
                </div>
                <div class="adm-section__body overflow-x-auto p-0">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Label shown to visitors</th>
                                <th class="text-center">Shown</th>
                                <th class="text-center">Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($preset as $name => $definition)
                                @php
                                    $locked = $definition['locked'] ?? false;
                                    $setting = $settings[$name] ?? ['enabled' => true, 'required' => false];
                                @endphp
                                <tr x-data="{ enabled: @js((bool) ($setting['enabled'] ?? false) || $locked), required: @js((bool) ($setting['required'] ?? false)) }">
                                    <td>
                                        <span class="block text-sm text-[#161311]">{{ $definition['label'] }}</span>
                                        <span class="block text-[0.7rem] uppercase tracking-[0.14em] text-[#6f675e]">{{ ['text' => 'Text', 'email' => 'Email', 'tel' => 'Telephone', 'date' => 'Date', 'choice' => 'Choice', 'number' => 'Number', 'textarea' => 'Long text'][$definition['input']] ?? $definition['input'] }}</span>
                                    </td>
                                    <td class="min-w-[14rem]">
                                        <input type="text" name="fields[{{ $name }}][label]" value="{{ $setting['label'] ?? '' }}" placeholder="{{ $definition['label'] }}" class="adm-input" :disabled="!enabled" aria-label="Label for {{ $definition['label'] }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" name="fields[{{ $name }}][enabled]" :value="enabled ? 1 : 0">
                                        <button type="button" class="adm-switch" :class="{ 'is-on': enabled, 'is-locked': {{ $locked ? 'true' : 'false' }} }" @click="if (! {{ $locked ? 'true' : 'false' }}) { enabled = !enabled; if (!enabled) required = false; $dispatch('cms-dirty') }" role="switch" :aria-checked="enabled.toString()" aria-label="Show {{ $definition['label'] }}"></button>
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" name="fields[{{ $name }}][required]" :value="required ? 1 : 0">
                                        <button type="button" class="adm-switch" :class="{ 'is-on': required, 'is-locked': {{ $locked ? 'true' : 'false' }} || !enabled }" @click="if (! {{ $locked ? 'true' : 'false' }} && enabled) { required = !required; $dispatch('cms-dirty') }" role="switch" :aria-checked="required.toString()" aria-label="Require {{ $definition['label'] }}"></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="adm-savebar" @cms-dirty.window="markDirty()">
                <div class="flex items-center gap-3 text-sm">
                    <span class="adm-savebar__dot" x-show="dirty"></span>
                    <span x-show="dirty">You have unsaved changes.</span>
                    <span x-show="!dirty" class="text-[#e8e9ea]/60">All changes saved.</span>
                </div>
                <button type="submit" class="adm-btn adm-btn--gold" :disabled="saving"><span x-text="saving ? 'Saving' : 'Save form'"></span></button>
            </div>
        </div>

        <aside class="space-y-5 xl:sticky xl:top-28 xl:self-start">
            <div class="adm-card space-y-5 p-7">
                <p class="adm-kicker">Publishing</p>
                <div>
                    <p class="adm-label">Template</p>
                    <p class="text-sm">{{ $typeLabel }}</p>
                </div>
                @unless ($system)
                    <label class="flex items-center justify-between gap-4" x-data="{ on: @js($form->is_active) }">
                        <span>
                            <span class="adm-label mb-0">Live</span>
                            <span class="block text-xs text-[#6f675e]">Paused forms and their funnels are hidden.</span>
                        </span>
                        <input type="hidden" name="is_active" :value="on ? 1 : 0">
                        <button type="button" class="adm-switch" :class="on && 'is-on'" @click="on = !on; $dispatch('cms-dirty')" role="switch" :aria-checked="on.toString()"></button>
                    </label>
                @endunless
                <div x-data="copyField(@js($form->publicUrl()))">
                    <p class="adm-label">Public page</p>
                    <div class="adm-copy">
                        <input type="text" readonly :value="text" aria-label="Public link">
                        <button type="button" @click="copy()" x-text="copied ? 'Copied' : 'Copy'"></button>
                    </div>
                </div>
                <a href="{{ $form->publicUrl() }}" target="_blank" rel="noopener noreferrer" class="adm-btn adm-btn--ghost w-full">Preview the form</a>
            </div>

            <div class="adm-card p-7">
                <p class="adm-kicker">Performance</p>
                <p class="adm-stat__value">{{ $form->leads_count }}</p>
                <p class="mt-1 text-xs text-[#6f675e]">{{ \Illuminate\Support\Str::plural('lead', $form->leads_count) }} received</p>
                <a href="{{ route('admin.leads.index', ['type' => $form->type]) }}" class="adm-link mt-5 inline-block">View leads</a>
                @if ($funnels->isNotEmpty())
                    <p class="adm-label mt-6">Funnels using this form</p>
                    <ul class="space-y-2 text-sm">
                        @foreach ($funnels as $funnel)
                            <li><a href="{{ route('admin.funnels.edit', $funnel) }}" class="hover:text-[#0e1e37]">{{ $funnel->name }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>

            @unless ($system)
                <button type="submit" form="delete-form" class="text-xs text-[#9c3b2e] underline-offset-4 hover:underline">Delete this form</button>
            @endunless
        </aside>
    </form>

    @unless ($system)
        <form id="delete-form" method="POST" action="{{ route('admin.forms.destroy', $form) }}" onsubmit="return confirm('Delete this form? Funnels using it will also be removed. Leads are kept.')">
            @csrf
            @method('DELETE')
        </form>
    @endunless
</x-layouts.admin>
