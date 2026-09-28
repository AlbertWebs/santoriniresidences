<x-layouts.admin title="Lead forms" kicker="Forms & funnels">
    <div class="grid gap-10 xl:grid-cols-[1fr_22rem]">
        <div>
            <div class="max-w-2xl">
                <span class="adm-rule"></span>
                <p class="adm-title mt-6 text-4xl md:text-5xl">Forms that feel <em class="text-[#0e1e37]">like an invitation.</em></p>
                <p class="mt-4 text-sm leading-relaxed text-[#6f675e]">Each form starts from a template: site visit, virtual visit, purchase, price list, investment pack or general enquiry. Choose which fields appear, which are required, and the words around them. Every form has its own shareable page and can power social funnels.</p>
            </div>

            <div class="mt-10 space-y-4">
                @foreach ($forms as $form)
                    <article class="adm-card grid gap-6 p-6 md:grid-cols-[1fr_auto] md:items-center">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="adm-pill">{{ $types[$form->type]['label'] ?? $form->type }}</span>
                                <span class="adm-pill {{ $form->is_active ? 'adm-pill--on' : 'adm-pill--off' }}">{{ $form->is_active ? 'Live' : 'Paused' }}</span>
                                @if (in_array($form->key, \App\Http\Controllers\Admin\LeadFormController::SYSTEM_FORMS, true))
                                    <span class="text-[0.68rem] uppercase tracking-[0.18em] text-[#6f675e]">Core page</span>
                                @endif
                            </div>
                            <h2 class="adm-title mt-4 text-3xl"><a href="{{ route('admin.forms.edit', $form) }}" class="hover:text-[#0e1e37]">{{ $form->title }}</a></h2>
                            <p class="mt-2 text-sm text-[#6f675e]">{{ count($form->activeFields()) }} fields · {{ $form->leads_count }} {{ \Illuminate\Support\Str::plural('lead', $form->leads_count) }} · {{ $form->funnels_count }} {{ \Illuminate\Support\Str::plural('funnel', $form->funnels_count) }}</p>
                            <div class="mt-4 max-w-lg" x-data="copyField(@js($form->publicUrl()))">
                                <div class="adm-copy">
                                    <input type="text" readonly :value="text" aria-label="Public link">
                                    <button type="button" @click="copy()" x-text="copied ? 'Copied' : 'Copy'"></button>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 md:flex-col md:items-stretch">
                            <a href="{{ route('admin.forms.edit', $form) }}" class="adm-btn adm-btn--sm">Edit form</a>
                            <a href="{{ $form->publicUrl() }}" target="_blank" rel="noopener noreferrer" class="adm-btn adm-btn--ghost adm-btn--sm">Preview</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <aside>
            <form method="POST" action="{{ route('admin.forms.store') }}" class="adm-card space-y-5 p-7 xl:sticky xl:top-28">
                @csrf
                <p class="adm-kicker">New form</p>
                <p class="adm-title text-3xl">Start from a template</p>
                <div class="space-y-2">
                    @foreach ($types as $value => $type)
                        <label class="block cursor-pointer border border-[#e6dfd3] px-4 py-3 transition has-[:checked]:border-[#0e1e37] has-[:checked]:bg-[#0e1e37]/[0.04]">
                            <span class="flex items-center gap-3">
                                <input type="radio" name="type" value="{{ $value }}" class="accent-[#0e1e37]" @checked(old('type', 'purchase') === $value)>
                                <span class="text-sm text-[#161311]">{{ $type['label'] }}</span>
                            </span>
                            <span class="mt-1 block pl-6 text-xs leading-relaxed text-[#6f675e]">{{ $type['description'] }}</span>
                        </label>
                    @endforeach
                </div>
                <div>
                    <label for="new-title" class="adm-label">Form name</label>
                    <input id="new-title" name="title" type="text" value="{{ old('title') }}" class="adm-input" placeholder="For example: Loft purchase interest" required>
                    @error('title')<p class="adm-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="adm-btn w-full">Create form</button>
            </form>
        </aside>
    </div>
</x-layouts.admin>
