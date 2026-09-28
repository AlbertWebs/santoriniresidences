<x-layouts.admin :title="$lead->name" kicker="Lead">
    <x-slot:actions>
        <a href="{{ route('admin.leads.index') }}" class="adm-btn adm-btn--ghost adm-btn--sm hidden md:inline-flex">All leads</a>
    </x-slot:actions>

    @php($whatsapp = preg_replace('/\D+/', '', (string) $lead->phone))

    <div class="grid gap-8 xl:grid-cols-[1fr_24rem]">
        <div class="space-y-6">
            <div class="adm-card p-8">
                <div class="flex flex-wrap items-start justify-between gap-6">
                    <div>
                        <span class="adm-pill adm-pill--{{ $lead->status }}">{{ $lead->statusLabel() }}</span>
                        <p class="adm-title mt-5 text-4xl">{{ $lead->name }}</p>
                        <p class="mt-2 text-sm text-[#6f675e]">{{ \App\Support\LeadFormTypes::label($lead->form_type) }} · received {{ $lead->created_at->format('j F Y \a\t H:i') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="mailto:{{ $lead->email }}" class="adm-btn adm-btn--sm">Email</a>
                        @if ($lead->phone)
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $lead->phone) }}" class="adm-btn adm-btn--ghost adm-btn--sm">Call</a>
                            @if (strlen($whatsapp) >= 9)
                                <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="adm-btn adm-btn--ghost adm-btn--sm">WhatsApp</a>
                            @endif
                        @endif
                    </div>
                </div>

                <dl class="mt-8 grid gap-x-8 gap-y-6 border-t border-[#efe9df] pt-8 sm:grid-cols-2">
                    <div>
                        <dt class="adm-label">Email</dt>
                        <dd class="break-words text-sm"><a href="mailto:{{ $lead->email }}" class="text-[#0e1e37] underline decoration-[#bca869]/60 underline-offset-4">{{ $lead->email }}</a></dd>
                    </div>
                    @foreach ($details as $label => $value)
                        <div>
                            <dt class="adm-label">{{ $label }}</dt>
                            <dd class="text-sm">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>

                @if ($lead->message)
                    <div class="mt-8 border-t border-[#efe9df] pt-8">
                        <p class="adm-label">Message</p>
                        <p class="mt-2 whitespace-pre-line border-l border-[#bca869] pl-5 font-serif text-xl leading-relaxed text-[#161311]">{{ $lead->message }}</p>
                    </div>
                @endif
            </div>

            <div class="adm-card p-8">
                <p class="adm-kicker">Attribution</p>
                <dl class="mt-6 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                    <div><dt class="adm-label">Source</dt><dd class="text-sm">{{ $lead->funnel ? 'Social funnel: '.$lead->funnel->name : 'Website' }}</dd></div>
                    <div><dt class="adm-label">Form</dt><dd class="text-sm">{{ $lead->form?->title ?? \App\Support\LeadFormTypes::label($lead->form_type) }}</dd></div>
                    <div><dt class="adm-label">UTM source</dt><dd class="text-sm">{{ $lead->utm_source ?: 'None' }}</dd></div>
                    <div><dt class="adm-label">UTM medium</dt><dd class="text-sm">{{ $lead->utm_medium ?: 'None' }}</dd></div>
                    <div><dt class="adm-label">UTM campaign</dt><dd class="text-sm">{{ $lead->utm_campaign ?: 'None' }}</dd></div>
                    <div><dt class="adm-label">Submitted on</dt><dd class="break-all text-sm">{{ $lead->meta['page'] ?? 'Unknown' }}</dd></div>
                    @if ($lead->referrer)
                        <div class="sm:col-span-2"><dt class="adm-label">Referrer</dt><dd class="break-all text-sm">{{ $lead->referrer }}</dd></div>
                    @endif
                </dl>
            </div>

            <div class="adm-card" id="documents" x-data="{ uploading: @js($errors->any() && old('_form') === 'lead-upload') }">
                <header class="adm-card__head">
                    <div>
                        <p class="adm-kicker">Documents</p>
                        <h3 class="adm-title mt-1.5 text-2xl">{{ $lead->documents->count() ? $lead->documents->count().' '.\Illuminate\Support\Str::plural('file', $lead->documents->count()).' for '.$lead->name : 'No files yet' }}</h3>
                    </div>
                    <button type="button" class="adm-btn adm-btn--sm" @click="uploading = !uploading" x-text="uploading ? 'Cancel' : 'Upload for this customer'"></button>
                </header>

                <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" x-show="uploading" x-cloak x-transition.opacity
                    class="grid gap-6 border-b border-[#efe9df] bg-[#fdfcfa] px-6 py-6 md:grid-cols-[1.4fr_1fr]" x-data="{ sending: false }" @submit="sending = true">
                    @csrf
                    <input type="hidden" name="_form" value="lead-upload">
                    <input type="hidden" name="lead_ids[]" value="{{ $lead->id }}">
                    @include('admin.documents.file-field', ['title' => old('title', ''), 'titleId' => 'lead-document-title'])
                    <div class="flex flex-col gap-5">
                        <div>
                            <label for="lead-document-category" class="adm-label">Category</label>
                            <select id="lead-document-category" name="category" class="adm-select">
                                @foreach (\App\Models\Document::CATEGORIES as $value => $label)
                                    <option value="{{ $value }}" @selected(old('category', $lead->status === 'won' ? 'sale-agreement' : 'offer-letter') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="lead-document-notes" class="adm-label">Private notes <small>Optional</small></label>
                            <textarea id="lead-document-notes" name="notes" rows="2" class="adm-textarea">{{ old('notes') }}</textarea>
                        </div>
                        <button type="submit" class="adm-btn mt-auto" :disabled="sending"><span x-text="sending ? 'Uploading' : 'Upload and link'"></span></button>
                    </div>
                </form>

                @forelse ($lead->documents as $document)
                    <div class="flex flex-wrap items-center gap-4 border-b border-[#efe9df] px-6 py-4" x-data="copyField(@js($document->shareUrl($lead)))">
                        <span class="adm-filetype adm-filetype--{{ \Illuminate\Support\Str::lower($document->extension()) }}">{{ $document->extension() }}</span>
                        <a href="{{ route('admin.documents.show', $document) }}" class="min-w-0 flex-1">
                            <span class="block truncate font-serif text-lg leading-tight text-[#161311]">{{ $document->title }}</span>
                            <span class="block truncate text-xs text-[#6f675e]">
                                {{ $document->categoryLabel() }} · {{ $document->humanSize() }} ·
                                {{ $document->pivot->downloads ? 'opened '.$document->pivot->downloads.'×, last '.\Illuminate\Support\Carbon::parse($document->pivot->last_downloaded_at)->diffForHumans() : 'not opened by the customer yet' }}
                            </span>
                        </a>
                        <div class="flex items-center gap-2">
                            <button type="button" class="adm-btn adm-btn--ghost adm-btn--sm" @click="copy()" title="A private link valid for {{ \App\Models\Document::SHARE_DAYS }} days" x-text="copied ? 'Copied' : 'Copy private link'"></button>
                            <a href="{{ route('admin.documents.download', $document) }}" class="adm-icon-btn" title="Download" aria-label="Download {{ $document->title }}">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 19h14" stroke="currentColor" stroke-width="1.5"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.leads.documents.detach', [$lead, $document]) }}" onsubmit="return confirm('Unlink this document from {{ addslashes($lead->name) }}? The file stays in the vault.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="adm-icon-btn" title="Unlink" aria-label="Unlink {{ $document->title }}">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.5"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="adm-empty py-10" x-show="!uploading">
                        <p>Offer letters, agreements and receipts for this customer will appear here.</p>
                    </div>
                @endforelse

                @if ($vault->isNotEmpty())
                    <form method="POST" action="{{ route('admin.leads.documents.attach', $lead) }}" class="flex flex-wrap items-end gap-3 px-6 py-5">
                        @csrf
                        <div class="min-w-0 flex-1">
                            <label for="attach-document" class="adm-label">Link a document from the vault</label>
                            <select id="attach-document" name="document_id" class="adm-select" required>
                                <option value="">Choose a document</option>
                                @foreach ($vault->groupBy('category') as $category => $items)
                                    <optgroup label="{{ \App\Models\Document::CATEGORIES[$category] ?? $category }}">
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="adm-btn adm-btn--ghost">Link</button>
                    </form>
                @endif
            </div>
        </div>

        <aside class="xl:sticky xl:top-28 xl:self-start">
            <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="adm-card space-y-6 p-7">
                @csrf
                @method('PATCH')
                <p class="adm-kicker">Follow up</p>

                <div>
                    <p class="adm-label">Status</p>
                    <div class="grid gap-2">
                        @foreach (\App\Models\Lead::STATUSES as $value => $label)
                            <label class="flex cursor-pointer items-center gap-3 border border-[#e6dfd3] px-3 py-2.5 text-sm transition has-[:checked]:border-[#0e1e37] has-[:checked]:bg-[#0e1e37]/[0.04]">
                                <input type="radio" name="status" value="{{ $value }}" class="accent-[#0e1e37]" @checked($lead->status === $value)>
                                <span class="adm-pill adm-pill--{{ $value }}">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                @if (in_array($lead->form_type, ['book-visit', 'schedule-visit'], true))
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="preferred_date" class="adm-label">Visit date</label>
                            <input id="preferred_date" type="date" name="preferred_date" value="{{ $lead->preferred_date?->toDateString() }}" class="adm-input">
                        </div>
                        <div>
                            <label for="preferred_time" class="adm-label">Time</label>
                            <select id="preferred_time" name="preferred_time" class="adm-select">
                                <option value="">Not set</option>
                                @foreach (\App\Support\LeadFormTypes::TIMES as $time)
                                    <option value="{{ $time }}" @selected($lead->preferred_time === $time)>{{ $time }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <div>
                    <label for="notes" class="adm-label">Private notes</label>
                    <textarea id="notes" name="notes" rows="6" class="adm-textarea" placeholder="Calls, preferences, next steps">{{ $lead->notes }}</textarea>
                </div>

                <button type="submit" class="adm-btn w-full">Save follow-up</button>
            </form>

            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" class="mt-4 text-right" onsubmit="return confirm('Delete this lead permanently?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-[#9c3b2e] underline-offset-4 hover:underline">Delete lead</button>
            </form>
        </aside>
    </div>
</x-layouts.admin>
