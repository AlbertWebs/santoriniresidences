@php
    use App\Models\Document;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;
@endphp

<x-layouts.admin :title="$document->title" kicker="Document vault">
    <x-slot:actions>
        <a href="{{ route('admin.legal.documents') }}" class="adm-btn adm-btn--ghost adm-btn--sm hidden md:inline-flex">All documents</a>
        <a href="{{ route('admin.documents.download', $document) }}" class="adm-btn adm-btn--sm">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 19h14" stroke="currentColor" stroke-width="1.6"/></svg>
            Download
        </a>
    </x-slot:actions>

    <div class="grid gap-8 xl:grid-cols-[1fr_26rem]">
        <div class="space-y-6">
            <div class="adm-card overflow-hidden">
                <div class="flex flex-wrap items-center gap-5 p-7">
                    <span class="adm-filetype adm-filetype--lg adm-filetype--{{ Str::lower($document->extension()) }}">{{ $document->extension() }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="adm-kicker">{{ $document->categoryLabel() }}</p>
                        <p class="adm-title mt-2 truncate text-3xl">{{ $document->title }}</p>
                        <p class="mt-1.5 truncate text-xs text-[#6f675e]">
                            {{ $document->original_name }} · {{ $document->humanSize() }} · uploaded {{ $document->created_at->format('j F Y') }}{{ $document->uploader ? ' by '.$document->uploader->name : '' }}
                        </p>
                    </div>
                </div>
                @if ($document->previewable())
                    <div class="adm-preview">
                        @if ($document->mime === 'application/pdf')
                            <iframe src="{{ route('admin.documents.download', [$document, 'preview' => 1]) }}#view=FitH" title="Preview of {{ $document->title }}"></iframe>
                        @else
                            <img src="{{ route('admin.documents.download', [$document, 'preview' => 1]) }}" alt="{{ $document->title }}">
                        @endif
                    </div>
                @else
                    <div class="adm-empty border-t border-[#efe9df]">
                        <p class="adm-title text-2xl">No preview for {{ $document->extension() }} files.</p>
                        <p>Download the file to open it in Word or Excel.</p>
                    </div>
                @endif
            </div>

            <div class="adm-card">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Customers</p>
                        <h3 class="adm-title mt-1.5 text-2xl">Shared with {{ $document->leads->count() }} {{ Str::plural('customer', $document->leads->count()) }}</h3>
                    </div>
                </header>
                @forelse ($document->leads as $lead)
                    <div class="grid gap-4 border-b border-[#efe9df] px-6 py-5 last:border-b-0 lg:grid-cols-[1fr_1.3fr] lg:items-center" x-data="copyField(@js($document->shareUrl($lead)))">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="flex min-w-0 items-center gap-3">
                            <span class="adm-avatar">{{ Str::upper(Str::substr($lead->name, 0, 1)) }}</span>
                            <span class="min-w-0">
                                <span class="block truncate font-serif text-lg leading-tight text-[#161311]">{{ $lead->name }}</span>
                                <span class="block truncate text-xs text-[#6f675e]">
                                    {{ $lead->email }} ·
                                    @if ($lead->pivot->downloads)
                                        opened {{ $lead->pivot->downloads }} {{ Str::plural('time', $lead->pivot->downloads) }}, last {{ Carbon::parse($lead->pivot->last_downloaded_at)->diffForHumans() }}
                                    @else
                                        not opened yet
                                    @endif
                                </span>
                            </span>
                        </a>
                        <div>
                            <div class="adm-copy">
                                <input type="text" readonly :value="text" aria-label="Private link for {{ $lead->name }}">
                                <button type="button" @click="copy()" x-text="copied ? 'Copied' : 'Copy link'"></button>
                            </div>
                            <p class="mt-1.5 text-[0.7rem] text-[#6f675e]">
                                Private link, valid for {{ Document::SHARE_DAYS }} days.
                                <a href="mailto:{{ $lead->email }}?subject={{ rawurlencode($document->title) }}&body={{ rawurlencode("Dear {$lead->name},\n\nPlease find your document here:\n".$document->shareUrl($lead)."\n\nThe link is valid for ".Document::SHARE_DAYS." days.\n\nSantorini Residences") }}" class="text-[#0e1e37] underline decoration-[#bca869]/60 underline-offset-4">Email it</a>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="adm-empty">
                        <p class="adm-title text-2xl">Not linked to anyone yet.</p>
                        <p>Add customers on the right to share this document with them.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <aside class="space-y-4 xl:sticky xl:top-28 xl:self-start">
            <form method="POST" action="{{ route('admin.documents.update', $document) }}" enctype="multipart/form-data" class="adm-card space-y-6 p-7"
                x-data="pageEditor()" x-ref="form" @input="markDirty()" @change="markDirty()" @cms-dirty="markDirty()" @submit="saving = true">
                @csrf
                @method('PUT')
                <p class="adm-kicker">Details</p>

                @include('admin.documents.file-field', ['required' => false, 'title' => old('title', $document->title)])

                <div>
                    <label for="category" class="adm-label">Category</label>
                    <select id="category" name="category" class="adm-select">
                        @foreach (Document::CATEGORIES as $value => $label)
                            <option value="{{ $value }}" @selected(old('category', $document->category) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="adm-error">{{ $message }}</p>@enderror
                </div>

                @include('admin.documents.customer-picker', [
                    'selected' => old('lead_ids') !== null ? \App\Models\Lead::whereIn('id', (array) old('lead_ids'))->get() : $document->leads,
                ])
                @error('lead_ids.*')<p class="adm-error">{{ $message }}</p>@enderror

                <div>
                    <label for="notes" class="adm-label">Private notes</label>
                    <textarea id="notes" name="notes" rows="4" class="adm-textarea" placeholder="Version, signatory, anything the team should know">{{ old('notes', $document->notes) }}</textarea>
                    @error('notes')<p class="adm-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="adm-btn w-full" :disabled="saving"><span x-text="saving ? 'Saving' : 'Save document'"></span></button>
            </form>

            <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" class="text-right" onsubmit="return confirm('Delete this document permanently? Private links already sent will stop working.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-[#9c3b2e] underline-offset-4 hover:underline">Delete document</button>
            </form>
        </aside>
    </div>
</x-layouts.admin>
