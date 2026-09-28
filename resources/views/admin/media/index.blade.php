<x-layouts.admin title="Media library" kicker="Website">
    <div x-data="mediaBrowser({ endpoint: @js(route('admin.media.library')), updateUrl: @js(route('admin.media.update', '__ID__')), items: @js($items) })" @media-uploaded="uploaded($event.detail)">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Images</p><p class="adm-stat__value" x-text="items.filter(i => i.kind === 'image').length">{{ $stats['images'] }}</p></div>
            <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Films</p><p class="adm-stat__value" x-text="items.filter(i => i.kind === 'video').length">{{ $stats['films'] }}</p></div>
            <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Uploaded here</p><p class="adm-stat__value" x-text="items.filter(i => i.is_upload).length">{{ $stats['uploads'] }}</p></div>
            <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Storage used</p><p class="adm-stat__value" x-text="window.cmsFormatBytes(items.filter(i => i.is_upload).reduce((t, i) => t + Number(i.size || 0), 0))">{{ number_format($stats['size'] / 1048576, 1) }} MB</p></div>
        </div>

        <div class="mt-8 grid gap-8 xl:grid-cols-[1fr_22rem]">
            <div class="min-w-0">
                <div class="adm-card p-6">
                    @include('admin.partials.uploader')
                </div>

                <div class="mt-8 flex flex-wrap items-center justify-between gap-4">
                    <div class="adm-tabs" role="tablist">
                        <button type="button" class="adm-tab" :class="kind === 'all' && 'is-active'" @click="kind = 'all'">All <span x-text="items.length"></span></button>
                        <button type="button" class="adm-tab" :class="kind === 'image' && 'is-active'" @click="kind = 'image'">Images <span x-text="items.filter(i => i.kind === 'image').length"></span></button>
                        <button type="button" class="adm-tab" :class="kind === 'video' && 'is-active'" @click="kind = 'video'">Films <span x-text="items.filter(i => i.kind === 'video').length"></span></button>
                    </div>
                    <input type="search" x-model="query" placeholder="Search by name or description" class="adm-input max-w-xs" aria-label="Search the library">
                </div>

                <div x-show="!filtered.length" x-cloak class="adm-card mt-6 py-20 text-center">
                    <p class="adm-title text-3xl">Nothing matches.</p>
                    <p class="mt-3 text-sm text-[#6f675e]">Try another search, or upload a new file above.</p>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-3 2xl:grid-cols-4">
                    <template x-for="item in filtered" :key="item.id">
                        <button type="button" class="adm-media-tile" :class="selectedId === item.id && 'is-selected'" @click="selectedId = selectedId === item.id ? null : item.id">
                            <div class="adm-media-tile__img relative">
                                <template x-if="item.kind === 'image'"><img :src="item.thumb || item.url" :alt="item.alt || ''" loading="lazy" decoding="async"></template>
                                <template x-if="item.kind === 'video'"><video :src="item.url + '#t=1'" muted playsinline preload="metadata"></video></template>
                                <span x-show="item.kind === 'video'" class="absolute bottom-2 left-2 bg-[#07152a]/80 px-2 py-0.5 text-[0.6rem] uppercase tracking-[0.18em] text-[#e3c992]">Film</span>
                            </div>
                            <span class="adm-media-tile__check"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="m5 12 4.5 4.5L19 7" stroke="currentColor" stroke-width="2"/></svg></span>
                            <div class="px-3 py-2.5">
                                <p class="truncate text-xs text-[#161311]" x-text="item.original_name"></p>
                                <p class="mt-0.5 text-[0.68rem] text-[#6f675e]" x-text="(item.width ? item.width + ' × ' + item.height + ' · ' : '') + item.human_size"></p>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            <aside class="xl:sticky xl:top-28 xl:self-start">
                <div class="adm-card" x-show="!selected">
                    <div class="p-7">
                        <p class="adm-kicker">Details</p>
                        <p class="adm-title mt-4 text-3xl">Select a file</p>
                        <p class="mt-3 text-sm leading-relaxed text-[#6f675e]">Choose any image or film to preview it, write its description and copy its link. Images you upload are resized to a maximum of 2560 pixels and converted to WebP for fast pages.</p>
                    </div>
                </div>
                <template x-if="selected">
                    <div class="adm-card">
                        <div class="adm-thumb aspect-[4/3] border-0 border-b">
                            <template x-if="selected.kind === 'image'"><img :src="selected.url" :alt="selected.alt || ''"></template>
                            <template x-if="selected.kind === 'video'"><video :src="selected.url" controls muted preload="metadata"></video></template>
                        </div>
                        <div class="space-y-5 p-6">
                            <div>
                                <p class="break-words text-sm text-[#161311]" x-text="selected.original_name"></p>
                                <p class="mt-1 text-xs text-[#6f675e]" x-text="(selected.width ? selected.width + ' × ' + selected.height + ' px · ' : '') + selected.human_size + ' · ' + (selected.is_upload ? 'Uploaded' : 'Original site file')"></p>
                            </div>
                            <div>
                                <label for="media-alt" class="adm-label">Description</label>
                                <textarea id="media-alt" rows="3" class="adm-textarea" x-model="selected.alt" placeholder="Describe what the image shows"></textarea>
                                <p class="adm-help">Used as the default description when this file is placed on a page.</p>
                            </div>
                            <div>
                                <p class="adm-label">Link</p>
                                <div class="adm-copy">
                                    <input type="text" readonly :value="selected.url" aria-label="File link">
                                    <button type="button" @click="copy(selected.url)">Copy</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-3 border-t border-[#efe9df] pt-5">
                                <button type="button" class="adm-btn adm-btn--danger adm-btn--sm" @click="remove()">Delete</button>
                                <button type="button" class="adm-btn adm-btn--sm" :disabled="saving" @click="saveAlt()" x-text="saving ? 'Saving' : 'Save description'"></button>
                            </div>
                        </div>
                    </div>
                </template>
            </aside>
        </div>
    </div>
</x-layouts.admin>
