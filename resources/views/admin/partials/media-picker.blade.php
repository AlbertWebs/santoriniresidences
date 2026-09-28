<div x-data="mediaBrowser({ endpoint: @js(route('admin.media.library')), updateUrl: @js(route('admin.media.update', '__ID__')), picker: true })"
    x-show="$store.picker.isOpen"
    x-cloak
    x-transition.opacity.duration.250ms
    class="adm-modal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="picker-title"
    @keydown.escape.window="$store.picker.close()"
    @media-uploaded="uploaded($event.detail)"
    @click.self="$store.picker.close()">
    <div class="adm-modal__panel">
        <div class="flex items-center justify-between gap-6 border-b border-[#e6dfd3] px-7 py-5">
            <div>
                <p class="adm-kicker">Media library</p>
                <h2 id="picker-title" class="adm-title mt-1 text-3xl" x-text="kind === 'video' ? 'Choose a film' : 'Choose an image'"></h2>
            </div>
            <div class="flex items-center gap-3">
                <input type="search" x-model="query" placeholder="Search by name or description" class="adm-input hidden w-72 md:block" aria-label="Search the library">
                <button type="button" class="adm-icon-btn" @click="$store.picker.close()" aria-label="Close">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.6"/></svg>
                </button>
            </div>
        </div>

        <div class="grid min-h-0 flex-1 overflow-hidden lg:grid-cols-[1fr_20rem]">
            <div class="min-h-0 overflow-y-auto p-7">
                <div x-show="loading" class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
                    <template x-for="n in 8" :key="n"><div class="aspect-[4/3] animate-pulse bg-[#efe9df]"></div></template>
                </div>
                <div x-show="!loading && loaded && !filtered.length" class="py-20 text-center">
                    <p class="adm-title text-3xl">Nothing here yet.</p>
                    <p class="mt-3 text-sm text-[#6f675e]">Upload a file on the right and it will appear in the library.</p>
                </div>
                <div x-show="!loading" class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
                    <template x-for="item in filtered" :key="item.id">
                        <button type="button" class="adm-media-tile" :class="selectedId === item.id && 'is-selected'" @click="selectedId = item.id" @dblclick="$store.picker.select(item)">
                            <div class="adm-media-tile__img">
                                <template x-if="item.kind === 'image'"><img :src="item.thumb || item.url" :alt="item.alt || ''" loading="lazy" decoding="async"></template>
                                <template x-if="item.kind === 'video'"><video :src="item.url + '#t=1'" muted playsinline preload="metadata"></video></template>
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

            <aside class="min-h-0 overflow-y-auto border-t border-[#e6dfd3] bg-white p-7 lg:border-l lg:border-t-0">
                @include('admin.partials.uploader', ['compact' => true, 'acceptFrom' => '$store.picker.kind'])
            </aside>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 border-t border-[#e6dfd3] bg-white px-7 py-4">
            <p class="min-w-0 truncate text-sm text-[#6f675e]">
                <span x-show="!selected">Select a file, or double-click to use it straight away.</span>
                <span x-show="selected" x-text="selected ? selected.original_name : ''" class="text-[#161311]"></span>
            </p>
            <div class="flex items-center gap-3">
                <button type="button" class="adm-btn adm-btn--ghost" @click="$store.picker.close()">Cancel</button>
                <button type="button" class="adm-btn" :disabled="!selected" @click="$store.picker.select(selected)" x-text="kind === 'video' ? 'Use this film' : 'Use this image'"></button>
            </div>
        </div>
    </div>
</div>
