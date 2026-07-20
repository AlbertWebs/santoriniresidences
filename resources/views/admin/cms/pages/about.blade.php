<x-layouts.admin title="About Us Editor">
    <div class="space-y-4" x-data="aboutPageEditor()">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('admin.cms.pages.index') }}" class="text-sm text-neutral-500 hover:text-neutral-700">&larr; Back to Pages Manager</a>
                <h2 class="mt-1 text-lg font-semibold text-neutral-900">About Us</h2>
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
                        <label class="admin-label">Company History</label>
                        <textarea rows="4" class="admin-input" x-model="content.history" placeholder="Founded in..."></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Mission Statement</label>
                        <textarea rows="3" class="admin-input" x-model="content.mission"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Core Values (one per line)</label>
                        <textarea rows="4" class="admin-input" x-model="content.values"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Team Profiles</label>
                        <template x-for="(member, index) in content.team" :key="member.id">
                            <div class="mb-3 rounded-xl border border-neutral-200 p-3">
                                <input type="text" class="admin-input mb-2" placeholder="Name" x-model="member.name">
                                <input type="text" class="admin-input mb-2" placeholder="Role" x-model="member.role">
                                <textarea rows="2" class="admin-input" placeholder="Bio" x-model="member.bio"></textarea>
                                <button type="button" class="mt-2 text-xs text-rose-600" @click="content.team.splice(index, 1)">Remove</button>
                            </div>
                        </template>
                        <button type="button" class="btn-secondary" @click="addTeamMember()">Add Team Member</button>
                    </div>
                </div>

                <div class="bg-neutral-50 p-5">
                    <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-neutral-400">Live Preview</p>
                    <div class="mx-auto max-w-md overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">
                        <div class="bg-neutral-900 px-5 py-8 text-white">
                            <p class="text-xs uppercase tracking-wider text-neutral-400">About Us</p>
                            <h3 class="mt-2 text-xl font-semibold" x-text="content.headline || 'Your Headline'"></h3>
                        </div>
                        <div class="space-y-5 p-5">
                            <section>
                                <h4 class="text-sm font-semibold text-neutral-900">Our History</h4>
                                <p class="mt-2 text-sm leading-relaxed text-neutral-600" x-text="content.history || 'Company history will appear here.'"></p>
                            </section>
                            <section>
                                <h4 class="text-sm font-semibold text-neutral-900">Mission</h4>
                                <p class="mt-2 text-sm leading-relaxed text-neutral-600" x-text="content.mission || 'Mission statement preview.'"></p>
                            </section>
                            <section>
                                <h4 class="text-sm font-semibold text-neutral-900">Core Values</h4>
                                <ul class="mt-2 space-y-1">
                                    <template x-for="value in valuesList" :key="value">
                                        <li class="flex items-center gap-2 text-sm text-neutral-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-accent-500"></span>
                                            <span x-text="value"></span>
                                        </li>
                                    </template>
                                </ul>
                            </section>
                            <section>
                                <h4 class="text-sm font-semibold text-neutral-900">Leadership Team</h4>
                                <div class="mt-3 space-y-3">
                                    <template x-for="member in content.team" :key="member.id">
                                        <div class="rounded-xl border border-neutral-200 p-3">
                                            <p class="text-sm font-medium text-neutral-900" x-text="member.name || 'Team Member'"></p>
                                            <p class="text-xs text-neutral-500" x-text="member.role || 'Role'"></p>
                                            <p class="mt-1 text-xs text-neutral-600" x-text="member.bio || 'Bio preview.'"></p>
                                        </div>
                                    </template>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-6 right-6 z-50">
            <div x-show="toast" x-transition class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-lg" style="display: none;">
                About page saved successfully.
            </div>
        </div>
    </div>

    <script>
        function aboutPageEditor() {
            return {
                toast: false,
                content: {
                    headline: 'Crafting Iconic Off-Plan Destinations',
                    history: 'Santorini Residences has guided premium buyers through off-plan acquisitions since 2012, delivering curated developments across the Mediterranean and Gulf regions.',
                    mission: 'To deliver transparent, design-led off-plan investment journeys backed by legal clarity and white-glove advisory.',
                    values: 'Integrity\nDesign Excellence\nClient Advocacy\nLong-term Stewardship',
                    team: [
                        { id: crypto.randomUUID(), name: 'Elena Markou', role: 'Managing Director', bio: '15+ years in luxury development advisory.' },
                        { id: crypto.randomUUID(), name: 'James Okonkwo', role: 'Head of Sales', bio: 'Specialist in cross-border off-plan settlements.' },
                    ],
                },
                get valuesList() {
                    return this.content.values.split('\n').map((line) => line.trim()).filter(Boolean);
                },
                addTeamMember() {
                    this.content.team.push({ id: crypto.randomUUID(), name: '', role: '', bio: '' });
                },
                save() {
                    this.toast = true;
                    setTimeout(() => this.toast = false, 10000);
                },
            };
        }
    </script>
</x-layouts.admin>
