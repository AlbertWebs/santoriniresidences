<x-layouts.admin title="Testimonials & Press">
    <form method="POST" action="{{ route('admin.cms.testimonials.update') }}" class="space-y-6" x-data="testimonialsEditor(@js($items))">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-neutral-900">Testimonials & Press</h2>
                <p class="text-sm text-neutral-500">Save entries as drafts, then publish them when you are ready. Published testimonials appear in the site footer and on the public page.</p>
            </div>
            <div class="flex gap-2">
                <button type="button" class="btn-secondary" @click="addItem()">Add entry</button>
                <button type="submit" class="btn-primary">Save changes</button>
            </div>
        </div>

        @if (session('status'))<p class="rounded-xl bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>@endif
        @if ($errors->any())<div class="rounded-xl bg-rose-50 p-3 text-sm text-rose-800">Please check the highlighted entries and try again.</div>@endif

        <template x-if="items.length === 0">
            <div class="admin-card p-8 text-center text-sm text-neutral-500">No entries yet. Add a testimonial or press mention to get started.</div>
        </template>

        <div class="grid gap-4 lg:grid-cols-2">
            <template x-for="(item, index) in items" :key="item.id">
                <article class="admin-card space-y-4 p-5">
                    <input type="hidden" :name="`items[${index}][id]`" x-model="item.id">
                    <div class="flex items-center justify-between gap-3">
                        <select class="admin-input max-w-[180px]" :name="`items[${index}][type]`" x-model="item.type" aria-label="Entry type">
                            <option value="Testimonial">Testimonial</option>
                            <option value="Press Mention">Press mention</option>
                            <option value="Success Story">Success story</option>
                        </select>
                        <button type="button" class="text-sm text-rose-600" @click="items.splice(index, 1)">Remove</button>
                    </div>
                    <input class="admin-input" :name="`items[${index}][name]`" x-model="item.name" required maxlength="160" placeholder="Client or publication name" aria-label="Client or publication name">
                    <input class="admin-input" :name="`items[${index}][headline]`" x-model="item.headline" maxlength="200" placeholder="Headline (optional)" aria-label="Headline">
                    <textarea class="admin-input" :name="`items[${index}][content]`" x-model="item.content" required maxlength="3000" rows="5" placeholder="Quote or press summary" aria-label="Quote or press summary"></textarea>
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-neutral-700">
                        <input type="checkbox" value="1" :name="`items[${index}][is_public]`" x-model="item.is_public" class="rounded border-neutral-300">
                        Show publicly on the website
                    </label>
                </article>
            </template>
        </div>

        <section class="admin-card p-5">
            <h3 class="mb-3 text-sm font-semibold text-neutral-900">Public preview</h3>
            <template x-if="items.filter(item => item.is_public && item.name && item.content).length === 0">
                <p class="text-sm text-neutral-500">Nothing is public yet. The footer link stays hidden until a complete entry is published.</p>
            </template>
            <div class="grid gap-4 md:grid-cols-2">
                <template x-for="item in items.filter(entry => entry.is_public && entry.name && entry.content)" :key="'preview-' + item.id">
                    <blockquote class="rounded-xl border border-neutral-200 bg-neutral-50 p-4">
                        <p class="text-sm italic text-neutral-700" x-text="item.content"></p>
                        <footer class="mt-3 text-xs font-medium text-neutral-900" x-text="item.name"></footer>
                        <p class="text-xs text-neutral-500" x-text="item.type"></p>
                    </blockquote>
                </template>
            </div>
        </section>
    </form>

    <script>
        function testimonialsEditor(initialItems) {
            return {
                items: initialItems.map(item => ({ is_public: false, headline: '', ...item })),
                addItem() {
                    this.items.push({ id: crypto.randomUUID(), type: 'Testimonial', name: '', headline: '', content: '', is_public: false });
                },
            };
        }
    </script>
</x-layouts.admin>
