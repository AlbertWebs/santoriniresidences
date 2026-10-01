@php
    $articles = [
        ['title' => 'Q3 Off-Plan Market Outlook: Mediterranean Coast', 'category' => 'Market Trends', 'status' => 'Published', 'date' => 'Jul 12, 2026'],
        ['title' => 'Aegean Crown: Structural Milestone Reached', 'category' => 'Construction Update', 'status' => 'Published', 'date' => 'Jul 8, 2026'],
        ['title' => 'Escrow Compliance Changes for 2026', 'category' => 'Investor Guide', 'status' => 'Draft', 'date' => '—'],
    ];
@endphp

<x-layouts.admin title="Blog / Market Insights">
    <div class="space-y-6" x-data="{
        showModal: false,
        query: '',
        status: 'all',
        category: 'all',
        excerpt: '',
        images: [],
        addArticleImage(media) {
            if (!this.images.some(image => image.id === media.id)) this.images.push(media);
        },
        removeArticleImage(id) {
            this.images = this.images.filter(image => image.id !== id);
        },
        articles: @js($articles),
        get filteredArticles() {
            const query = this.query.trim().toLowerCase();
            return this.articles.filter(article =>
                (this.status === 'all' || article.status === this.status)
                && (this.category === 'all' || article.category === this.category)
                && article.title.toLowerCase().includes(query)
            );
        }
    }" @media-uploaded="addArticleImage($event.detail)">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="adm-kicker">Content library</p>
                <h2 class="adm-title mt-1 text-3xl">Market insights</h2>
                <p class="mt-2 max-w-2xl text-sm text-[#6f675e]">Manage articles, construction updates, and investor guides from one place.</p>
            </div>
            <button class="adm-btn adm-btn--sm" @click="showModal = true">New article</button>
        </div>

        <section class="adm-card overflow-hidden">
            <div class="grid gap-3 border-b border-[#efe9df] bg-white p-4 md:grid-cols-[minmax(15rem,1fr)_12rem_14rem_auto] md:items-center">
                <label class="sr-only" for="article-search">Search articles</label>
                <input id="article-search" type="search" x-model.debounce.200ms="query" class="adm-input" placeholder="Search article titles">
                <label class="sr-only" for="article-status">Filter by status</label>
                <select id="article-status" x-model="status" class="adm-select">
                    <option value="all">All statuses</option>
                    <option value="Published">Published</option>
                    <option value="Draft">Draft</option>
                </select>
                <label class="sr-only" for="article-category">Filter by category</label>
                <select id="article-category" x-model="category" class="adm-select">
                    <option value="all">All categories</option>
                    <option value="Market Trends">Market trends</option>
                    <option value="Construction Update">Construction updates</option>
                    <option value="Investor Guide">Investor guides</option>
                </select>
                <button type="button" class="adm-btn adm-btn--ghost adm-btn--sm" @click="query = ''; status = 'all'; category = 'all'">Reset</button>
            </div>
            <div class="adm-table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Published</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="article in filteredArticles" :key="article.title">
                            <tr>
                                <td>
                                    <span class="block max-w-xl font-serif text-lg leading-tight text-[#161311]" x-text="article.title"></span>
                                    <span class="mt-1 block text-xs text-[#6f675e]">Market insights</span>
                                </td>
                                <td><span class="adm-pill" x-text="article.category"></span></td>
                                <td><span class="adm-pill" :class="article.status === 'Published' ? 'adm-pill--on' : 'adm-pill--off'" x-text="article.status"></span></td>
                                <td class="whitespace-nowrap text-sm tabular-nums text-[#6f675e]" x-text="article.date"></td>
                                <td class="text-right">
                                    <button type="button" class="adm-btn adm-btn--ghost adm-btn--sm" @click="showModal = true">Edit</button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredArticles.length === 0">
                            <td colspan="5" class="py-12 text-center text-sm text-[#6f675e]">No articles match these filters. Try another search or reset the filters.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#efe9df] px-5 py-3 text-xs text-[#6f675e]">Showing <span class="font-medium text-[#161311]" x-text="filteredArticles.length"></span> of {{ count($articles) }} sample articles</div>
        </section>

        <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
            <div class="w-full max-w-2xl rounded-2xl border border-[#e6dfd3] bg-white p-6 shadow-2xl">
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
                        <label for="article-excerpt" class="admin-label">Short description / excerpt</label>
                        <textarea id="article-excerpt" name="excerpt" rows="3" maxlength="300" x-model="excerpt" class="adm-textarea" placeholder="A concise summary for article cards and search results"></textarea>
                        <p class="adm-help"><span x-text="excerpt.length"></span>/300 characters. Keep it brief and clear.</p>
                    </div>
                    <div>
                        <p class="admin-label">Article images <small>Optional · multiple images</small></p>
                        @include('admin.partials.uploader', ['accept' => 'image', 'compact' => true])
                        <div x-show="images.length" x-cloak class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <template x-for="image in images" :key="image.id">
                                <figure class="group relative overflow-hidden border border-[#e6dfd3] bg-[#f8f6f1]">
                                    <img :src="image.thumb || image.url" :alt="image.alt || image.original_name" class="aspect-[4/3] w-full object-cover">
                                    <figcaption class="truncate px-2.5 py-2 text-xs text-[#6f675e]" x-text="image.original_name"></figcaption>
                                    <input type="hidden" name="images[]" :value="image.path">
                                    <button type="button" class="absolute right-2 top-2 border border-white/60 bg-[#07152a]/80 px-2 py-1 text-xs text-white opacity-100 transition sm:opacity-0 sm:group-hover:opacity-100" @click="removeArticleImage(image.id)" :aria-label="'Remove ' + image.original_name">Remove</button>
                                </figure>
                            </template>
                        </div>
                        <p class="adm-help">Images are optimized and added to the shared media library. You can select more than one.</p>
                    </div>
                    <div x-data="richTextEditor()">
                        <label id="article-content-label" class="admin-label">Article content</label>
                        <div class="adm-editor">
                            <div class="adm-editor__toolbar" role="toolbar" aria-label="Text formatting">
                                <button type="button" class="adm-editor__tool font-bold" @mousedown.prevent @click="format('bold')" title="Bold" aria-label="Bold">B</button>
                                <button type="button" class="adm-editor__tool italic" @mousedown.prevent @click="format('italic')" title="Italic" aria-label="Italic">I</button>
                                <button type="button" class="adm-editor__tool underline" @mousedown.prevent @click="format('underline')" title="Underline" aria-label="Underline">U</button>
                                <span class="mx-1 self-stretch border-l border-[#e6dfd3]" aria-hidden="true"></span>
                                <button type="button" class="adm-editor__tool font-medium" @mousedown.prevent @click="block('h2')" title="Heading" aria-label="Heading 2">H2</button>
                                <button type="button" class="adm-editor__tool font-medium" @mousedown.prevent @click="block('h3')" title="Subheading" aria-label="Heading 3">H3</button>
                                <button type="button" class="adm-editor__tool" @mousedown.prevent @click="format('insertUnorderedList')" title="Bulleted list" aria-label="Bulleted list">• List</button>
                                <button type="button" class="adm-editor__tool" @mousedown.prevent @click="format('insertOrderedList')" title="Numbered list" aria-label="Numbered list">1. List</button>
                                <span class="mx-1 self-stretch border-l border-[#e6dfd3]" aria-hidden="true"></span>
                                <button type="button" class="adm-editor__tool" @mousedown.prevent @click="addLink()" title="Add link" aria-label="Add link">Link</button>
                                <button type="button" class="adm-editor__tool" @mousedown.prevent @click="format('removeFormat')" title="Clear formatting" aria-label="Clear formatting">Clear</button>
                            </div>
                            <div x-ref="editor" class="adm-editor__content" contenteditable="true" role="textbox" aria-labelledby="article-content-label" aria-multiline="true" data-placeholder="Write your article here…" @input="sync()"></div>
                        </div>
                        <textarea name="content" class="sr-only" tabindex="-1" aria-hidden="true" x-model="html"></textarea>
                        <p class="adm-help">Use headings, emphasis, lists, and links to structure your article.</p>
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
