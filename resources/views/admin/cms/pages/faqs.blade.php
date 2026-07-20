<x-layouts.admin title="FAQs & Guides">
    <div class="space-y-6" x-data="faqsEditor()">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('admin.cms.pages.index') }}" class="text-sm text-neutral-500 hover:text-neutral-700">&larr; Back to Pages Manager</a>
                <h2 class="mt-1 text-lg font-semibold text-neutral-900">FAQs & Guides</h2>
                <p class="text-sm text-neutral-500">Manage accordion blocks explaining off-plan purchasing, staging payments, and escrow.</p>
            </div>
            <div class="flex gap-2">
                <button class="btn-secondary" @click="addBlock()">Add FAQ Block</button>
                <button class="btn-primary" @click="save()">Save Changes</button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-4">
                <template x-for="(block, index) in blocks" :key="block.id">
                    <div class="admin-card p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-neutral-400" x-text="'Block ' + (index + 1)"></span>
                            <button type="button" class="text-xs text-rose-600" @click="blocks.splice(index, 1)">Remove</button>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="admin-label">Question</label>
                                <input type="text" class="admin-input" x-model="block.question">
                            </div>
                            <div>
                                <label class="admin-label">Answer</label>
                                <textarea rows="4" class="admin-input" x-model="block.answer"></textarea>
                            </div>
                            <div>
                                <label class="admin-label">Category</label>
                                <select class="admin-input" x-model="block.category">
                                    <option>Off-Plan Purchasing</option>
                                    <option>Staging Payments</option>
                                    <option>Escrow</option>
                                    <option>General</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="admin-card p-5">
                <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-neutral-400">Accordion Preview</p>
                <div class="space-y-2">
                    <template x-for="block in blocks" :key="block.id">
                        <div class="overflow-hidden rounded-xl border border-neutral-200">
                            <button type="button" class="flex w-full items-center justify-between px-4 py-3 text-left text-sm font-medium text-neutral-900 hover:bg-neutral-50"
                                @click="block.open = !block.open">
                                <span x-text="block.question || 'Untitled question'"></span>
                                <svg class="h-4 w-4 text-neutral-500 transition" :class="block.open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                            </button>
                            <div x-show="block.open" x-transition class="border-t border-neutral-100 px-4 py-3 text-sm text-neutral-600" x-text="block.answer || 'Answer preview.'"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="fixed bottom-6 right-6 z-50">
            <div x-show="toast" x-transition class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-lg" style="display: none;">
                FAQs saved successfully.
            </div>
        </div>
    </div>

    <script>
        function faqsEditor() {
            return {
                toast: false,
                blocks: [
                    { id: crypto.randomUUID(), question: 'How does off-plan purchasing work?', answer: 'You reserve a unit during construction and follow a milestone-based payment schedule until handover.', category: 'Off-Plan Purchasing', open: true },
                    { id: crypto.randomUUID(), question: 'What are staging payments?', answer: 'Staging payments are scheduled installments tied to construction progress, typically 10–20% per milestone.', category: 'Staging Payments', open: false },
                    { id: crypto.randomUUID(), question: 'How is escrow protected?', answer: 'Funds are held in regulated escrow accounts and released only when verified construction milestones are met.', category: 'Escrow', open: false },
                ],
                addBlock() {
                    this.blocks.push({ id: crypto.randomUUID(), question: '', answer: '', category: 'General', open: false });
                },
                save() {
                    this.toast = true;
                    setTimeout(() => this.toast = false, 10000);
                },
            };
        }
    </script>
</x-layouts.admin>
