<x-layouts.admin title="Services Editor">
    <div class="space-y-4" x-data="servicesPageEditor()">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('admin.cms.pages.index') }}" class="text-sm text-neutral-500 hover:text-neutral-700">&larr; Back to Pages Manager</a>
                <h2 class="mt-1 text-lg font-semibold text-neutral-900">Services</h2>
            </div>
            <button class="btn-primary" @click="save()">Save Changes</button>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="grid min-h-[720px] lg:grid-cols-2">
                <div class="space-y-5 border-b border-neutral-200 p-5 lg:border-b-0 lg:border-r">
                    <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Editor</p>

                    <div>
                        <label class="admin-label">Page Headline</label>
                        <input type="text" class="admin-input" x-model="content.headline">
                    </div>
                    <div>
                        <label class="admin-label">Intro Paragraph</label>
                        <textarea rows="3" class="admin-input" x-model="content.intro"></textarea>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="admin-label mb-0">Service Offerings</label>
                            <button type="button" class="btn-secondary" @click="addService()">Add Service</button>
                        </div>
                        <template x-for="(service, index) in content.services" :key="service.id">
                            <div class="rounded-xl border border-neutral-200 p-3">
                                <input type="text" class="admin-input mb-2" placeholder="Service title" x-model="service.title">
                                <textarea rows="3" class="admin-input" placeholder="Service description" x-model="service.description"></textarea>
                                <button type="button" class="mt-2 text-xs text-rose-600" @click="content.services.splice(index, 1)">Remove</button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-neutral-50 p-5">
                    <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-neutral-400">Live Preview</p>
                    <div class="mx-auto max-w-md overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">
                        <div class="bg-neutral-900 px-5 py-8 text-white">
                            <p class="text-xs uppercase tracking-wider text-neutral-400">Services</p>
                            <h3 class="mt-2 text-xl font-semibold" x-text="content.headline || 'Services Headline'"></h3>
                            <p class="mt-3 text-sm text-neutral-300" x-text="content.intro || 'Intro paragraph preview.'"></p>
                        </div>
                        <div class="space-y-3 p-5">
                            <template x-for="service in content.services" :key="service.id">
                                <article class="rounded-xl border border-neutral-200 p-4 transition hover:border-neutral-300">
                                    <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M4 12h16M12 4v16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                    </div>
                                    <h4 class="text-sm font-semibold text-neutral-900" x-text="service.title || 'Service Title'"></h4>
                                    <p class="mt-1 text-xs leading-relaxed text-neutral-600" x-text="service.description || 'Service description preview.'"></p>
                                </article>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-6 right-6 z-50">
            <div x-show="toast" x-transition class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-lg" style="display: none;">
                Services page saved successfully.
            </div>
        </div>
    </div>

    <script>
        function servicesPageEditor() {
            return {
                toast: false,
                content: {
                    headline: 'Advisory Services for Off-Plan Investors',
                    intro: 'From acquisition strategy to handover management, our team supports every stage of your property journey.',
                    services: [
                        { id: crypto.randomUUID(), title: 'Off-Plan Investment Consulting', description: 'Market analysis, yield modeling, and project shortlisting tailored to your portfolio goals.' },
                        { id: crypto.randomUUID(), title: 'Architectural Advisory', description: 'Design reviews, finish selections, and customization guidance for premium units.' },
                        { id: crypto.randomUUID(), title: 'Property Management', description: 'Tenant placement, maintenance coordination, and asset performance reporting.' },
                        { id: crypto.randomUUID(), title: 'Resale Services', description: 'Exit strategy planning, valuation support, and buyer matching for secondary sales.' },
                    ],
                },
                addService() {
                    this.content.services.push({ id: crypto.randomUUID(), title: '', description: '' });
                },
                save() {
                    this.toast = true;
                    setTimeout(() => this.toast = false, 10000);
                },
            };
        }
    </script>
</x-layouts.admin>
