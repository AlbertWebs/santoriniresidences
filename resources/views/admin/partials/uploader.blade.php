@php($compact = $compact ?? false)
<div x-data="uploader({ endpoint: @js(route('admin.media.chunk')), accept: @js($accept ?? 'all') })" @if (! empty($acceptFrom)) x-effect="accept = {{ $acceptFrom }}" @endif>
    <div class="adm-dropzone {{ $compact ? 'adm-dropzone--compact' : '' }}"
        :class="over && 'is-over'"
        role="button"
        tabindex="0"
        aria-label="Upload files"
        @click="browse()"
        @keydown.enter.prevent="browse()"
        @keydown.space.prevent="browse()"
        @dragover.prevent="over = true"
        @dragenter.prevent="over = true"
        @dragleave.prevent="over = false"
        @drop.prevent="drop($event)">
        <span class="adm-dropzone__mark" aria-hidden="true">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M12 16V4m0 0-4.5 4.5M12 4l4.5 4.5M4 15v4.5h16V15" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <p class="adm-title {{ $compact ? 'text-xl' : 'text-[1.7rem]' }}">Drop files <em class="text-[#0e1e37]">to upload</em></p>
        <p class="text-xs leading-relaxed text-[#6f675e]">
            or <span class="adm-link">browse your computer</span>
            <span class="mt-2 block" x-text="accept === 'video' ? 'MP4 films up to 600 MB, uploaded in secure 5 MB parts.' : (accept === 'image' ? 'JPG, PNG or WebP up to 25 MB. Images are optimised to WebP.' : 'JPG, PNG or WebP up to 25 MB. MP4 films up to 600 MB.')"></span>
        </p>
    </div>
    <input type="file" x-ref="input" class="sr-only" tabindex="-1" :accept="acceptAttr" :multiple="multiple" @change="add($event.target.files)">

    <div x-show="uploads.length" x-cloak class="mt-4">
        <div class="mb-2 flex items-center justify-between">
            <p class="adm-kicker adm-kicker--navy">Uploads</p>
            <button type="button" class="text-xs text-[#6f675e] hover:text-[#0e1e37]" x-show="!busy" @click="clearFinished()">Clear list</button>
        </div>
        <ul class="space-y-2">
            <template x-for="upload in uploads" :key="upload.id">
                <li class="adm-upload" :class="{ 'is-done': upload.state === 'done', 'is-error': ['error', 'cancelled'].includes(upload.state) }">
                    <div class="adm-upload__thumb flex items-center justify-center text-[#bca869]">
                        <img x-show="upload.preview" :src="upload.preview" alt="">
                        <svg x-show="!upload.preview" class="h-5 w-5" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" stroke="currentColor" stroke-width="1.3"/><path d="m10 9 5 3-5 3z" fill="currentColor"/></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-baseline justify-between gap-3">
                            <p class="truncate text-sm text-[#161311]" x-text="upload.name"></p>
                            <p class="shrink-0 text-[0.7rem] text-[#6f675e]" x-text="upload.sizeLabel"></p>
                        </div>
                        <div class="adm-progress" :class="upload.state === 'processing' && 'is-processing'" role="progressbar" :aria-valuenow="upload.progress" aria-valuemin="0" aria-valuemax="100">
                            <div class="adm-progress__bar" :style="`width: ${['error', 'cancelled'].includes(upload.state) ? 100 : upload.progress}%`"></div>
                        </div>
                        <p class="mt-1.5 text-[0.72rem]" :class="['error', 'cancelled'].includes(upload.state) ? 'text-[#9c3b2e]' : (upload.state === 'done' ? 'text-[#2f6b4f]' : 'text-[#6f675e]')" x-text="stateLabel(upload)"></p>
                    </div>
                    <div>
                        <button type="button" class="adm-icon-btn" x-show="['queued', 'uploading', 'processing'].includes(upload.state)" @click="cancel(upload)" title="Cancel upload" aria-label="Cancel upload">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.6"/></svg>
                        </button>
                        <button type="button" class="adm-icon-btn" x-show="!['queued', 'uploading', 'processing'].includes(upload.state)" @click="clear(upload)" title="Remove from list" aria-label="Remove from list">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M5 12h14" stroke="currentColor" stroke-width="1.6"/></svg>
                        </button>
                    </div>
                </li>
            </template>
        </ul>
    </div>
</div>
