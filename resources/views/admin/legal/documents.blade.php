@php
    use App\Models\Document;
    use Illuminate\Support\Number;
    use Illuminate\Support\Str;

    $reopen = $errors->any() && old('_form') === 'upload';
@endphp

<x-layouts.admin title="Document vault" kicker="Legal">
    <x-slot:actions>
        <button type="button" class="adm-btn adm-btn--sm" @click="$dispatch('open-upload')">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.6"/></svg>
            Upload document
        </button>
    </x-slot:actions>

    <div x-data="{ uploading: @js($reopen) }" @open-upload.window="uploading = true">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Documents</p><p class="adm-stat__value">{{ number_format($stats['total']) }}</p></div>
            <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Customers with files</p><p class="adm-stat__value">{{ number_format($stats['customers']) }}</p></div>
            <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Not yet linked</p><p class="adm-stat__value">{{ number_format($stats['unlinked']) }}</p></div>
            <div class="adm-stat"><p class="adm-kicker adm-kicker--navy">Storage used</p><p class="adm-stat__value">{{ Number::fileSize($stats['size'], precision: 1) }}</p></div>
        </div>

        <div class="mt-8 adm-tabs">
            <a href="{{ route('admin.legal.documents', array_merge($filters, ['category' => null])) }}" @class(['adm-tab', 'is-active' => empty($filters['category'])])>All <span>{{ $stats['total'] }}</span></a>
            @foreach (Document::CATEGORIES as $value => $label)
                <a href="{{ route('admin.legal.documents', array_merge($filters, ['category' => $value])) }}" @class(['adm-tab', 'is-active' => ($filters['category'] ?? null) === $value])>{{ $label }} <span>{{ $categoryCounts[$value] ?? 0 }}</span></a>
            @endforeach
        </div>

        <form method="GET" class="adm-card mt-5 grid gap-3 p-4 md:grid-cols-[1.6fr_1fr_auto]">
            @if (! empty($filters['category']))
                <input type="hidden" name="category" value="{{ $filters['category'] }}">
            @endif
            <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="adm-input" placeholder="Search by title, file name or customer" aria-label="Search documents">
            <select name="linked" class="adm-select" aria-label="Customer link">
                <option value="">Linked and unlinked</option>
                <option value="linked" @selected(($filters['linked'] ?? '') === 'linked')>Linked to a customer</option>
                <option value="unlinked" @selected(($filters['linked'] ?? '') === 'unlinked')>Not linked yet</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="adm-btn">Filter</button>
                @if (array_filter($filters))
                    <a href="{{ route('admin.legal.documents') }}" class="adm-btn adm-btn--ghost">Clear</a>
                @endif
            </div>
        </form>

        <div class="adm-card mt-5 overflow-x-auto">
            @if ($documents->isEmpty())
                <div class="adm-empty py-20">
                    <p class="adm-title text-3xl">{{ array_filter($filters) ? 'Nothing matches.' : 'The vault is empty.' }}</p>
                    <p class="max-w-md">{{ array_filter($filters) ? 'Try a different search or category.' : 'Upload offer letters, sale agreements and payment schedules, then link each one to the customers it belongs to.' }}</p>
                    @unless (array_filter($filters))
                        <button type="button" class="adm-btn mt-5" @click="uploading = true">Upload the first document</button>
                    @endunless
                </div>
            @else
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Category</th>
                            <th>Customers</th>
                            <th class="text-right">Size</th>
                            <th class="text-right">Updated</th>
                            <th><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.documents.show', $document) }}" class="flex items-center gap-4">
                                        <span class="adm-filetype adm-filetype--{{ Str::lower($document->extension()) }}">{{ $document->extension() }}</span>
                                        <span class="min-w-0">
                                            <span class="block max-w-xs truncate font-serif text-lg leading-tight text-[#161311]">{{ $document->title }}</span>
                                            <span class="block max-w-xs truncate text-xs text-[#6f675e]">{{ $document->original_name }}</span>
                                        </span>
                                    </a>
                                </td>
                                <td class="whitespace-nowrap text-sm">{{ $document->categoryLabel() }}</td>
                                <td>
                                    @if ($document->leads->isEmpty())
                                        <span class="text-xs italic text-[#9a9187]">Not linked</span>
                                    @else
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            @foreach ($document->leads->take(2) as $lead)
                                                <a href="{{ route('admin.leads.show', $lead) }}" class="adm-chip adm-chip--link" title="{{ $lead->email }}">
                                                    <span class="adm-chip__initial">{{ Str::upper(Str::substr($lead->name, 0, 1)) }}</span>
                                                    {{ $lead->name }}
                                                </a>
                                            @endforeach
                                            @if ($document->leads->count() > 2)
                                                <a href="{{ route('admin.documents.show', $document) }}" class="text-xs text-[#6f675e] hover:text-[#0e1e37]">+{{ $document->leads->count() - 2 }} more</a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap text-right text-sm tabular-nums text-[#6f675e]">{{ $document->humanSize() }}</td>
                                <td class="whitespace-nowrap text-right text-xs text-[#6f675e]" title="{{ $document->updated_at->format('j F Y, H:i') }}">{{ $document->updated_at->diffForHumans() }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        @if ($document->previewable())
                                            <a href="{{ route('admin.documents.download', [$document, 'preview' => 1]) }}" target="_blank" rel="noopener" class="adm-icon-btn" title="Preview" aria-label="Preview {{ $document->title }}">
                                                <svg viewBox="0 0 24 24" fill="none"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z" stroke="currentColor" stroke-width="1.4"/><circle cx="12" cy="12" r="2.8" stroke="currentColor" stroke-width="1.4"/></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.documents.download', $document) }}" class="adm-icon-btn" title="Download" aria-label="Download {{ $document->title }}">
                                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 19h14" stroke="currentColor" stroke-width="1.5"/></svg>
                                        </a>
                                        <a href="{{ route('admin.documents.show', $document) }}" class="adm-icon-btn" title="Details and customers" aria-label="Open {{ $document->title }}">
                                            <svg viewBox="0 0 24 24" fill="none"><path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="1.5"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if ($documents->hasPages())
            <div class="mt-6">{{ $documents->links() }}</div>
        @endif

        <div x-show="uploading" x-cloak x-transition.opacity.duration.250ms class="adm-modal" role="dialog" aria-modal="true" aria-labelledby="upload-title"
            @keydown.escape.window="uploading = false" @click.self="uploading = false">
            <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="adm-modal__panel adm-modal__panel--narrow"
                x-data="{ sending: false }" @submit="sending = true">
                @csrf
                <input type="hidden" name="_form" value="upload">
                <div class="flex items-center justify-between gap-6 border-b border-[#e6dfd3] px-7 py-5">
                    <div>
                        <p class="adm-kicker">Document vault</p>
                        <h2 id="upload-title" class="adm-title mt-1 text-3xl">Upload a document</h2>
                    </div>
                    <button type="button" class="adm-icon-btn" @click="uploading = false" aria-label="Close">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.6"/></svg>
                    </button>
                </div>

                <div class="min-h-0 flex-1 space-y-6 overflow-y-auto px-7 py-6">
                    @include('admin.documents.file-field', ['title' => old('title', '')])

                    <div>
                        <label for="upload-category" class="adm-label">Category</label>
                        <select id="upload-category" name="category" class="adm-select">
                            @foreach (Document::CATEGORIES as $value => $label)
                                <option value="{{ $value }}" @selected(old('category', $filters['category'] ?? 'offer-letter') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="adm-error">{{ $message }}</p>@enderror
                    </div>

                    @include('admin.documents.customer-picker', [
                        'selected' => $reopen ? \App\Models\Lead::whereIn('id', (array) old('lead_ids', []))->get() : [],
                        'id' => 'upload-customers',
                    ])
                    @error('lead_ids.*')<p class="adm-error">{{ $message }}</p>@enderror

                    <div>
                        <label for="upload-notes" class="adm-label">Private notes <small>Optional</small></label>
                        <textarea id="upload-notes" name="notes" rows="3" class="adm-textarea" placeholder="Version, signatory, anything the team should know">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 border-t border-[#e6dfd3] bg-white px-7 py-4">
                    <p class="text-xs text-[#6f675e]">Stored privately. Only signed-in staff and linked customers can open it.</p>
                    <div class="flex shrink-0 gap-3">
                        <button type="button" class="adm-btn adm-btn--ghost" @click="uploading = false">Cancel</button>
                        <button type="submit" class="adm-btn" :disabled="sending"><span x-text="sending ? 'Uploading' : 'Upload'"></span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
