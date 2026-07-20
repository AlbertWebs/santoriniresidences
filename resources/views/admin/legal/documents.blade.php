<x-layouts.admin title="Document Vault">
    <div class="space-y-6" x-data="documentVault()">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-neutral-900">Document Vault</h2>
                <p class="text-sm text-neutral-500">Upload, categorize, and link downloadable legal templates for public or authenticated access.</p>
            </div>
            <button class="btn-primary" @click="showUpload = true">Upload Document</button>
        </div>

        <div class="flex flex-wrap gap-2">
            <template x-for="category in categories" :key="category">
                <button type="button"
                    class="rounded-full px-3 py-1.5 text-xs font-medium transition"
                    :class="activeCategory === category ? 'bg-neutral-900 text-white' : 'border border-neutral-200 text-neutral-600 hover:bg-neutral-50'"
                    @click="activeCategory = category"
                    x-text="category">
                </button>
            </template>
        </div>

        <section class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Document</th>
                            <th class="px-5 py-3">Category</th>
                            <th class="px-5 py-3">Access</th>
                            <th class="px-5 py-3">Size</th>
                            <th class="px-5 py-3">Updated</th>
                            <th class="px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 bg-white">
                        <template x-for="doc in filteredDocuments" :key="doc.id">
                            <tr class="hover:bg-neutral-50/60">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.8"/><path d="M14 3v5h5" stroke="currentColor" stroke-width="1.8"/></svg>
                                        </div>
                                        <span class="font-medium text-neutral-900" x-text="doc.name"></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-neutral-600" x-text="doc.category"></td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                                        :class="doc.access === 'Public' ? 'bg-sky-100 text-sky-700' : 'bg-violet-100 text-violet-700'"
                                        x-text="doc.access"></span>
                                </td>
                                <td class="px-5 py-4 text-neutral-600" x-text="doc.size"></td>
                                <td class="px-5 py-4 text-neutral-600" x-text="doc.updated"></td>
                                <td class="px-5 py-4">
                                    <button class="text-sm font-medium text-neutral-700 hover:text-neutral-900">Download</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </section>

        <div x-show="showUpload" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
            <div class="w-full max-w-lg rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-semibold text-neutral-900">Upload Document</h3>
                <form class="mt-4 space-y-4" @submit.prevent="uploadDocument()">
                    <div>
                        <label class="admin-label">Document Name</label>
                        <input type="text" class="admin-input" x-model="uploadForm.name" required>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Category</label>
                            <select class="admin-input" x-model="uploadForm.category">
                                <template x-for="cat in categories.filter(c => c !== 'All')" :key="cat">
                                    <option :value="cat" x-text="cat"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="admin-label">Access Level</label>
                            <select class="admin-input" x-model="uploadForm.access">
                                <option>Public</option>
                                <option>Authenticated</option>
                            </select>
                        </div>
                    </div>
                    <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-neutral-300 bg-neutral-50 px-4 py-8 text-center">
                        <svg class="mb-2 h-6 w-6 text-neutral-500" viewBox="0 0 24 24" fill="none"><path d="M12 16V8m0 0-3 3m3-3 3 3M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <p class="text-sm text-neutral-600">Drop PDF or DOCX file here</p>
                        <input type="file" class="hidden" accept=".pdf,.doc,.docx">
                    </label>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="btn-secondary" @click="showUpload = false">Cancel</button>
                        <button type="submit" class="btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function documentVault() {
            return {
                showUpload: false,
                activeCategory: 'All',
                categories: ['All', 'Offer Letters', 'Sale Agreements', 'Escrow Terms', 'Architectural Blueprints'],
                uploadForm: { name: '', category: 'Offer Letters', access: 'Public' },
                documents: [
                    { id: 1, name: 'Sample Offer Letter Template', category: 'Offer Letters', access: 'Public', size: '245 KB', updated: 'Jul 1, 2026' },
                    { id: 2, name: 'Standard Sale Agreement v3.2', category: 'Sale Agreements', access: 'Authenticated', size: '1.2 MB', updated: 'Jun 28, 2026' },
                    { id: 3, name: 'Escrow Terms & Conditions', category: 'Escrow Terms', access: 'Public', size: '380 KB', updated: 'Jun 15, 2026' },
                    { id: 4, name: 'Aegean Crown Floor Plans', category: 'Architectural Blueprints', access: 'Authenticated', size: '4.8 MB', updated: 'Jul 10, 2026' },
                ],
                get filteredDocuments() {
                    if (this.activeCategory === 'All') return this.documents;
                    return this.documents.filter((doc) => doc.category === this.activeCategory);
                },
                uploadDocument() {
                    this.documents.unshift({
                        id: crypto.randomUUID(),
                        name: this.uploadForm.name,
                        category: this.uploadForm.category,
                        access: this.uploadForm.access,
                        size: '—',
                        updated: 'Just now',
                    });
                    this.showUpload = false;
                    this.uploadForm = { name: '', category: 'Offer Letters', access: 'Public' };
                },
            };
        }
    </script>
</x-layouts.admin>
