<x-layouts.admin title="Testimonials & Press">
    <div class="space-y-6" x-data="testimonialsEditor()">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-neutral-900">Testimonials & Press</h2>
                <p class="text-sm text-neutral-500">Manage client reviews, success stories, and media mentions.</p>
            </div>
            <button class="btn-primary" @click="addItem()">Add Entry</button>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <template x-for="(item, index) in items" :key="item.id">
                <div class="admin-card p-5">
                    <div class="mb-3 flex items-center justify-between">
                        <select class="admin-input max-w-[160px]" x-model="item.type">
                            <option>Testimonial</option>
                            <option>Press Mention</option>
                            <option>Success Story</option>
                        </select>
                        <button type="button" class="text-xs text-rose-600" @click="items.splice(index, 1)">Remove</button>
                    </div>
                    <div class="space-y-3">
                        <input type="text" class="admin-input" placeholder="Client or publication name" x-model="item.name">
                        <input type="text" class="admin-input" placeholder="Headline or quote excerpt" x-model="item.headline">
                        <textarea rows="4" class="admin-input" placeholder="Full quote or press summary" x-model="item.content"></textarea>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" :id="'featured-' + item.id" x-model="item.featured" class="rounded border-neutral-300">
                            <label :for="'featured-' + item.id" class="text-sm text-neutral-600">Feature on homepage</label>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <section class="admin-card p-5">
            <h3 class="mb-4 text-sm font-semibold text-neutral-900">Public Preview</h3>
            <div class="grid gap-4 md:grid-cols-2">
                <template x-for="item in items.filter(i => i.featured)" :key="'preview-' + item.id">
                    <blockquote class="rounded-xl border border-neutral-200 bg-neutral-50 p-4">
                        <p class="text-sm italic text-neutral-700" x-text="item.content || 'Quote preview.'"></p>
                        <footer class="mt-3 text-xs font-medium text-neutral-900" x-text="item.name || 'Name'"></footer>
                        <p class="text-xs text-neutral-500" x-text="item.type"></p>
                    </blockquote>
                </template>
            </div>
        </section>
    </div>

    <script>
        function testimonialsEditor() {
            return {
                items: [
                    { id: crypto.randomUUID(), type: 'Testimonial', name: 'Sarah & Michael Chen', headline: 'Seamless off-plan purchase', content: 'The team guided us through every milestone with complete transparency. Our Aegean Crown villa exceeded expectations.', featured: true },
                    { id: crypto.randomUUID(), type: 'Press Mention', name: 'Luxury Property Weekly', headline: 'Top Off-Plan Developer Advisory 2026', content: 'Recognized for excellence in client advisory and escrow transparency.', featured: true },
                ],
                addItem() {
                    this.items.push({ id: crypto.randomUUID(), type: 'Testimonial', name: '', headline: '', content: '', featured: false });
                },
            };
        }
    </script>
</x-layouts.admin>
