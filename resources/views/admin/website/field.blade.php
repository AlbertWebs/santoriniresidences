@php
    $type = $field['type'];
    $wide = in_array($type, ['textarea', 'lines', 'image', 'video', 'repeater', 'link'], true);
@endphp

<div @class(['md:col-span-2' => $wide])>
    @if ($type === 'text')
        <label for="{{ $id }}" class="adm-label">{{ $field['label'] }}</label>
        <input id="{{ $id }}" name="{{ $name }}" type="text" value="{{ $value }}" @class(['adm-input', 'adm-input--serif' => $field['display'] ?? false])>

    @elseif ($type === 'textarea')
        <label for="{{ $id }}" class="adm-label">{{ $field['label'] }}</label>
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ strlen((string) $value) > 260 ? 5 : 3 }}" class="adm-textarea">{{ $value }}</textarea>

    @elseif ($type === 'lines')
        <label for="{{ $id }}" class="adm-label">{{ $field['label'] }}</label>
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ max(3, count((array) $value) + 1) }}" class="adm-textarea">{{ implode("\n", (array) $value) }}</textarea>

    @elseif ($type === 'select')
        <label for="{{ $id }}" class="adm-label">{{ $field['label'] }}</label>
        <select id="{{ $id }}" name="{{ $name }}" class="adm-select">
            @foreach ($field['options'] as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected($value === $optionValue)>{{ $optionLabel }}</option>
            @endforeach
        </select>

    @elseif ($type === 'link')
        <p class="adm-label">{{ $field['label'] }}</p>
        <div class="grid gap-3 sm:grid-cols-[1fr_1.2fr]">
            <input name="{{ $name }}[label]" type="text" value="{{ $value['label'] ?? '' }}" class="adm-input" placeholder="Label" aria-label="{{ $field['label'] }} label">
            <input name="{{ $name }}[url]" type="text" value="{{ $value['url'] ?? '' }}" class="adm-input font-mono text-[0.82rem]" placeholder="/book-a-visit or https://" aria-label="{{ $field['label'] }} link">
        </div>
        <p class="adm-help">Leave the label empty to hide this link. Use a site path such as /book-a-visit, an anchor such as #location, or a full web address.</p>

    @elseif (in_array($type, ['image', 'video'], true))
        <p class="adm-label">{{ $field['label'] }}</p>
        <div x-data="mediaField(@js((string) $value === (string) $field['default'] ? '' : (string) $value), @js($type), @js((string) $field['default']))" class="adm-image-field">
            <input type="hidden" name="{{ $name }}" :value="value">
            <div class="adm-thumb adm-image-field__preview">
                <template x-if="preview && kind === 'image'"><img :src="preview" alt=""></template>
                <template x-if="preview && kind === 'video'"><video :src="preview + '#t=1'" muted playsinline preload="metadata"></video></template>
                <div x-show="!preview" class="flex h-full items-center justify-center text-xs text-[#6f675e]">No {{ $type === 'video' ? 'film' : 'image' }}</div>
            </div>
            <div class="min-w-0">
                <p class="truncate font-mono text-[0.75rem] text-[#0e1e37]" x-text="value || fallback || 'Nothing selected'"></p>
                <p class="mt-1 text-xs text-[#6f675e]" x-text="value ? 'Custom selection' : (fallback ? 'Original {{ $type === 'video' ? 'film' : 'image' }}' : '')"></p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="button" class="adm-btn adm-btn--sm" @click="choose()">{{ $type === 'video' ? 'Choose film' : 'Choose image' }}</button>
                    <button type="button" class="adm-btn adm-btn--ghost adm-btn--sm" x-show="value" @click="clear()">Use original</button>
                </div>
            </div>
        </div>

    @elseif ($type === 'repeater')
        @php
            $blank = array_map(fn ($sub) => $sub['type'] === 'select' ? $sub['default'] : '', $field['fields']);
            $linesFields = array_keys(array_filter($field['fields'], fn ($sub) => $sub['type'] === 'lines'));
        @endphp
        <div x-data="repeater(@js(array_values((array) $value)), @js($blank), @js($linesFields))">
            <div class="flex items-baseline justify-between gap-4">
                <p class="adm-label mb-0">{{ $field['label'] }} <small x-text="items.length + (items.length === 1 ? ' item' : ' items')"></small></p>
            </div>
            <input type="hidden" name="{{ $name }}" value="" x-show="false" :disabled="items.length > 0">

            <ol class="mt-3 space-y-2">
                <template x-for="(item, index) in items" :key="item._key">
                    <li class="adm-repeater-item">
                        <div class="adm-repeater-item__head">
                            <span class="adm-repeater-item__index" x-text="String(index + 1).padStart(2, '0')"></span>
                            <button type="button" class="min-w-0 flex-1 truncate text-left text-sm text-[#161311]" @click="item._open = !item._open" x-text="item['{{ $field['title_field'] }}'] || 'Untitled'"></button>
                            <div class="flex items-center gap-1">
                                <button type="button" class="adm-icon-btn" @click="move(index, -1)" :disabled="index === 0" title="Move up" aria-label="Move up"><svg viewBox="0 0 24 24" fill="none"><path d="m6 15 6-6 6 6" stroke="currentColor" stroke-width="1.6"/></svg></button>
                                <button type="button" class="adm-icon-btn" @click="move(index, 1)" :disabled="index === items.length - 1" title="Move down" aria-label="Move down"><svg viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="1.6"/></svg></button>
                                <button type="button" class="adm-icon-btn" @click="duplicate(index)" title="Duplicate" aria-label="Duplicate"><svg viewBox="0 0 24 24" fill="none"><rect x="8" y="8" width="12" height="12" stroke="currentColor" stroke-width="1.5"/><path d="M16 8V4H4v12h4" stroke="currentColor" stroke-width="1.5"/></svg></button>
                                <button type="button" class="adm-icon-btn hover:!text-[#9c3b2e]" @click="remove(index)" title="Remove" aria-label="Remove"><svg viewBox="0 0 24 24" fill="none"><path d="M5 7h14M10 7V4h4v3M7 7l1 13h8l1-13" stroke="currentColor" stroke-width="1.5"/></svg></button>
                                <button type="button" class="adm-icon-btn" @click="item._open = !item._open" :aria-expanded="item._open.toString()" aria-label="Toggle"><svg class="transition" :class="item._open && 'rotate-180'" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="1.6"/></svg></button>
                            </div>
                        </div>
                        <div x-show="item._open" class="grid gap-x-5 gap-y-5 p-4 md:grid-cols-2">
                            @foreach ($field['fields'] as $subKey => $sub)
                                @php($subName = "'{$name}[' + index + '][{$subKey}]'")
                                <div @class(['md:col-span-2' => in_array($sub['type'], ['textarea', 'lines', 'image'], true)])>
                                    <p class="adm-label">{{ $sub['label'] }}</p>
                                    @if ($sub['type'] === 'text')
                                        <input type="text" class="adm-input" :name="{{ $subName }}" x-model="item['{{ $subKey }}']">
                                    @elseif ($sub['type'] === 'textarea')
                                        <textarea rows="3" class="adm-textarea" :name="{{ $subName }}" x-model="item['{{ $subKey }}']"></textarea>
                                    @elseif ($sub['type'] === 'lines')
                                        <textarea rows="3" class="adm-textarea" :name="{{ $subName }}" x-model="item['{{ $subKey }}']"></textarea>
                                        <p class="adm-help">{{ $sub['help'] }}</p>
                                    @elseif ($sub['type'] === 'select')
                                        <select class="adm-select" :name="{{ $subName }}" x-model="item['{{ $subKey }}']">
                                            @foreach ($sub['options'] as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                                            @endforeach
                                        </select>
                                    @elseif ($sub['type'] === 'image')
                                        <input type="hidden" :name="{{ $subName }}" :value="item['{{ $subKey }}']">
                                        <div class="adm-image-field">
                                            <div class="adm-thumb adm-image-field__preview">
                                                <img x-show="item['{{ $subKey }}']" :src="preview(item['{{ $subKey }}'])" alt="">
                                                <div x-show="!item['{{ $subKey }}']" class="flex h-full items-center justify-center text-xs text-[#6f675e]">No image</div>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate font-mono text-[0.75rem] text-[#0e1e37]" x-text="item['{{ $subKey }}'] || 'Nothing selected'"></p>
                                                @if ($sub['help'])
                                                    <p class="adm-help">{{ $sub['help'] }}</p>
                                                @endif
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    <button type="button" class="adm-btn adm-btn--sm" @click="choose(item, '{{ $subKey }}')">Choose image</button>
                                                    <button type="button" class="adm-btn adm-btn--ghost adm-btn--sm" x-show="item['{{ $subKey }}']" @click="item['{{ $subKey }}'] = ''; $dispatch('cms-dirty')">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </li>
                </template>
            </ol>

            <button type="button" class="adm-btn adm-btn--ghost adm-btn--sm mt-3" @click="add()">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8"/></svg>
                Add item
            </button>
        </div>
    @endif

    @if (! empty($field['help']) && ! in_array($type, ['link'], true))
        <p class="adm-help">{{ $field['help'] }}</p>
    @endif
</div>
