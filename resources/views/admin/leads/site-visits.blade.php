<x-layouts.admin title="Site Visit Scheduler">
    <div class="space-y-6" x-data="siteVisitScheduler()">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-neutral-900">Site Visit Scheduler</h2>
                <p class="text-sm text-neutral-500">Track confirmed physical and virtual site visit appointments with clients and assigned agents.</p>
            </div>
            <button class="btn-primary" @click="showModal = true">Schedule Visit</button>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="admin-card p-5 lg:col-span-1">
                <h3 class="mb-4 text-sm font-semibold text-neutral-900">Upcoming This Week</h3>
                <div class="space-y-3">
                    <template x-for="visit in visits" :key="visit.id">
                        <div class="rounded-xl border border-neutral-200 p-3">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-neutral-900" x-text="visit.client"></p>
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium"
                                    :class="visit.type === 'Virtual' ? 'bg-violet-100 text-violet-700' : 'bg-sky-100 text-sky-700'"
                                    x-text="visit.type"></span>
                            </div>
                            <p class="mt-1 text-xs text-neutral-500" x-text="visit.project"></p>
                            <p class="mt-2 text-xs text-neutral-600" x-text="visit.datetime"></p>
                            <p class="text-xs text-neutral-500">Agent: <span x-text="visit.agent"></span></p>
                        </div>
                    </template>
                </div>
            </section>

            <section class="admin-card overflow-hidden lg:col-span-2">
                <div class="border-b border-neutral-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-neutral-900">All Scheduled Visits</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-left text-sm">
                        <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500">
                            <tr>
                                <th class="px-5 py-3">Client</th>
                                <th class="px-5 py-3">Project</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Date & Time</th>
                                <th class="px-5 py-3">Agent</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 bg-white">
                            <template x-for="visit in visits" :key="'table-' + visit.id">
                                <tr class="hover:bg-neutral-50/60">
                                    <td class="px-5 py-4 font-medium text-neutral-900" x-text="visit.client"></td>
                                    <td class="px-5 py-4 text-neutral-600" x-text="visit.project"></td>
                                    <td class="px-5 py-4 text-neutral-600" x-text="visit.type"></td>
                                    <td class="px-5 py-4 text-neutral-600" x-text="visit.datetime"></td>
                                    <td class="px-5 py-4 text-neutral-600" x-text="visit.agent"></td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700" x-text="visit.status"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
            <div class="w-full max-w-lg rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-semibold text-neutral-900">Schedule Site Visit</h3>
                <form class="mt-4 space-y-4" @submit.prevent="scheduleVisit()">
                    <div>
                        <label class="admin-label">Client Name</label>
                        <input type="text" class="admin-input" x-model="form.client" required>
                    </div>
                    <div>
                        <label class="admin-label">Project</label>
                        <select class="admin-input" x-model="form.project">
                            <option>Aegean Crown Villas</option>
                            <option>Marina Heights</option>
                            <option>Azure Dunes Residences</option>
                        </select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Visit Type</label>
                            <select class="admin-input" x-model="form.type">
                                <option>Physical</option>
                                <option>Virtual</option>
                            </select>
                        </div>
                        <div>
                            <label class="admin-label">Assigned Agent</label>
                            <select class="admin-input" x-model="form.agent">
                                <option>James Okonkwo</option>
                                <option>Elena Markou</option>
                                <option>Sofia Petrov</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Date & Time</label>
                        <input type="datetime-local" class="admin-input" x-model="form.datetime" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="btn-secondary" @click="showModal = false">Cancel</button>
                        <button type="submit" class="btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function siteVisitScheduler() {
            return {
                showModal: false,
                form: { client: '', project: 'Aegean Crown Villas', type: 'Physical', agent: 'James Okonkwo', datetime: '' },
                visits: [
                    { id: 1, client: 'David Okoro', project: 'Aegean Crown Villas', type: 'Physical', datetime: 'Jul 22, 2026 · 10:00 AM', agent: 'James Okonkwo', status: 'Confirmed' },
                    { id: 2, client: 'Amira Hassan', project: 'Marina Heights', type: 'Virtual', datetime: 'Jul 23, 2026 · 3:00 PM', agent: 'Elena Markou', status: 'Confirmed' },
                    { id: 3, client: 'Thomas Berg', project: 'Azure Dunes Residences', type: 'Physical', datetime: 'Jul 25, 2026 · 11:30 AM', agent: 'Sofia Petrov', status: 'Confirmed' },
                ],
                scheduleVisit() {
                    this.visits.unshift({
                        id: crypto.randomUUID(),
                        client: this.form.client,
                        project: this.form.project,
                        type: this.form.type,
                        datetime: this.form.datetime.replace('T', ' · '),
                        agent: this.form.agent,
                        status: 'Confirmed',
                    });
                    this.showModal = false;
                    this.form = { client: '', project: 'Aegean Crown Villas', type: 'Physical', agent: 'James Okonkwo', datetime: '' };
                },
            };
        }
    </script>
</x-layouts.admin>
