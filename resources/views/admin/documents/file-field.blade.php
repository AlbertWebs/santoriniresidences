@php
    $required = $required ?? true;
    $accept = collect(\App\Models\Document::EXTENSIONS)->map(fn ($ext) => '.'.$ext)->implode(',');
@endphp
<div x-data="documentFile({ title: @js($title ?? '') })" class="space-y-5">
    <div>
        <label class="adm-dropzone adm-dropzone--compact relative" :class="over && 'is-over'"
            @dragover.prevent="over = true" @dragleave.prevent="over = false" @drop.prevent="drop($event)">
            <input type="file" name="file" x-ref="file" accept="{{ $accept }}" @change="pick($event.target.files)" @required($required)
                class="absolute inset-0 h-full w-full cursor-pointer opacity-0" aria-label="{{ $required ? 'Choose a file' : 'Replace the file' }}">
            <span class="adm-dropzone__mark">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M12 16V5m0 0-4 4m4-4 4 4M5 15v3a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
            </span>
            <span x-show="!name">
                <span class="block font-serif text-lg text-[#161311]">{{ $required ? 'Drop a document here' : 'Drop a new version here' }}</span>
                <span class="mt-1 block text-xs text-[#6f675e]">or click to browse. PDF, Word, Excel, JPG or PNG up to {{ \App\Models\Document::MAX_KILOBYTES / 1024 }} MB.</span>
            </span>
            <span x-show="name" x-cloak>
                <span class="block max-w-xs truncate font-serif text-lg text-[#161311]" x-text="name"></span>
                <span class="mt-1 block text-xs text-[#6f675e]"><span x-text="size"></span> · ready to upload</span>
            </span>
        </label>
        @error('file')<p class="adm-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="{{ $titleId ?? 'document-title' }}" class="adm-label">Title <small>Defaults to the file name</small></label>
        <input id="{{ $titleId ?? 'document-title' }}" name="title" type="text" x-model="title" @input="titleTouched = true" maxlength="160" class="adm-input adm-input--serif" placeholder="Offer letter, residence 1204">
        @error('title')<p class="adm-error">{{ $message }}</p>@enderror
    </div>
</div>
