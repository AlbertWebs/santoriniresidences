<x-layouts.admin title="Blog / Market Insights">
    <div class="space-y-6" x-data="{ showModal: false }">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-neutral-900">Blog / Market Insights</h2>
                <p class="text-sm text-neutral-500">Publish articles, market trends, and construction update newsletters for organic SEO.</p>
            </div>
            <button class="btn-primary" @click="showModal = true">New Article</button>
        </div>

        <section class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Title</th>
                            <th class="px-5 py-3">Category</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Published</th>
                            <th class="px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 bg-white">
                        @foreach ([
                            ['title' => 'Q3 Off-Plan Market Outlook: Mediterranean Coast', 'category' => 'Market Trends', 'status' => 'Published', 'statusStyle' => 'bg-emerald-100 text-emerald-700', 'date' => 'Jul 12, 2026'],
                            ['title' => 'Aegean Crown: Structural Milestone Reached', 'category' => 'Construction Update', 'status' => 'Published', 'statusStyle' => 'bg-emerald-100 text-emerald-700', 'date' => 'Jul 8, 2026'],
                            ['title' => 'Escrow Compliance Changes for 2026', 'category' => 'Investor Guide', 'status' => 'Draft', 'statusStyle' => 'bg-amber-100 text-amber-800', 'date' => '-'],
                        ] as $article)
                            <tr class="hover:bg-neutral-50/60">
                                <td class="px-5 py-4 font-medium text-neutral-900">{{ $article['title'] }}</td>
                                <td class="px-5 py-4 text-neutral-600">{{ $article['category'] }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $article['statusStyle'] }}">{{ $article['status'] }}</span>
                                </td>
                                <td class="px-5 py-4 text-neutral-600">{{ $article['date'] }}</td>
                                <td class="px-5 py-4">
                                    <button class="text-sm font-medium text-neutral-700 hover:text-neutral-900">Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
            <div class="w-full max-w-2xl rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-semibold text-neutral-900">Create Article</h3>
                <form class="mt-4 space-y-4">
                    <div>
                        <label class="admin-label">Title</label>
                        <input type="text" class="admin-input" placeholder="Article headline">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Category</label>
                            <select class="admin-input">
                                <option>Market Trends</option>
                                <option>Construction Update</option>
                                <option>Investor Guide</option>
                                <option>Newsletter</option>
                            </select>
                        </div>
                        <div>
                            <label class="admin-label">Status</label>
                            <select class="admin-input">
                                <option>Draft</option>
                                <option>Published</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Content</label>
                        <textarea rows="8" class="admin-input" placeholder="Write your article content..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="btn-secondary" @click="showModal = false">Cancel</button>
                        <button type="button" class="btn-primary" @click="showModal = false">Save Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
