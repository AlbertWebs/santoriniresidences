<x-layouts.admin :title="$schema['label']" kicker="Website">
    <x-slot:actions>
        <a href="{{ route('admin.website.index') }}" class="adm-btn adm-btn--ghost adm-btn--sm hidden md:inline-flex">All pages</a>
    </x-slot:actions>

    <div x-data="pageEditor()" @cms-dirty="markDirty()" class="mx-auto max-w-5xl">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <div class="max-w-2xl">
                <span class="adm-rule"></span>
                <p class="mt-5 text-sm leading-relaxed text-[#6f675e]">{{ $schema['description'] }}</p>
            </div>
            <a href="{{ $siteUrl }}" target="_blank" rel="noopener noreferrer" class="adm-link">View this page</a>
        </div>

        <nav class="mt-8 flex flex-wrap gap-2" aria-label="Sections">
            @foreach ($sections as $key => $section)
                <a href="#section-{{ $key }}" class="adm-pill hover:border-[#bca869]" @click="$dispatch('open-section', '{{ $key }}')">{{ $section['label'] }}</a>
            @endforeach
        </nav>

        <form x-ref="form" method="POST" action="{{ route('admin.website.update', $pageKey) }}" class="mt-10 space-y-4" @input="markDirty()" @change="markDirty()" @submit="saving = true" novalidate>
            @csrf
            @method('PUT')

            @foreach ($sections as $key => $section)
                <section id="section-{{ $key }}" class="adm-section scroll-mt-28" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }" :class="open && 'is-open'" @open-section.window="if ($event.detail === '{{ $key }}') open = true">
                    <button type="button" class="adm-section__toggle" @click="open = !open" :aria-expanded="open.toString()">
                        <span class="adm-section__index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="min-w-0">
                            <span class="adm-title block text-2xl">{{ $section['label'] }}</span>
                            @if ($section['help'])
                                <span class="mt-1 block text-xs text-[#6f675e]">{{ $section['help'] }}</span>
                            @endif
                        </span>
                        <span class="flex items-center gap-4">
                            @if (in_array($key, $customised, true))
                                <span class="adm-pill adm-pill--on hidden sm:inline-flex">Edited</span>
                            @endif
                            <svg class="adm-section__chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="1.6"/></svg>
                        </span>
                    </button>

                    <div x-show="open" x-cloak x-transition.opacity.duration.250ms class="adm-section__body">
                        <div class="grid gap-x-6 gap-y-7 md:grid-cols-2">
                            @foreach ($section['fields'] as $fieldKey => $field)
                                @include('admin.website.field', [
                                    'name' => "content[{$key}][{$fieldKey}]",
                                    'id' => "f-{$key}-{$fieldKey}",
                                    'field' => $field,
                                    'value' => $values[$key][$fieldKey] ?? $field['default'],
                                ])
                            @endforeach
                        </div>

                        <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-t border-[#efe9df] pt-6">
                            <a href="{{ $siteUrl }}{{ $section['anchor'] ?? '' }}" target="_blank" rel="noopener noreferrer" class="adm-link">View on site</a>
                            @if (in_array($key, $customised, true))
                                <button type="submit" name="reset" value="{{ $key }}" class="adm-btn adm-btn--danger adm-btn--sm" formnovalidate onclick="return confirm('Restore this section to its original copy? Saved edits to this section, and any unsaved changes on this page, will be lost.')">Restore original</button>
                            @endif
                        </div>
                    </div>
                </section>
            @endforeach

            <div class="adm-savebar mt-8">
                <div class="flex items-center gap-3 text-sm">
                    <span class="adm-savebar__dot" x-show="dirty"></span>
                    <span x-show="dirty">You have unsaved changes.</span>
                    <span x-show="!dirty" class="text-[#e8e9ea]/60">All changes saved. <span class="hidden sm:inline">Press Ctrl + S to save at any time.</span></span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" class="adm-btn adm-btn--ghost adm-btn--ghost-light adm-btn--sm" x-show="dirty" @click="discard()">Discard</button>
                    <button type="submit" class="adm-btn adm-btn--gold" :disabled="saving">
                        <span x-text="saving ? 'Saving' : 'Save and publish'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>
