<x-layouts.admin title="Data Backup">
    <div class="space-y-6" x-data="backupCenter()">
        <section class="adm-card p-5">
            <h2 class="text-lg font-semibold text-neutral-900">Data Backup Center</h2>
            <p class="mt-1 text-sm text-neutral-500">Generate snapshots of admin data and download backup archives.</p>
        </section>

        <section class="adm-card overflow-hidden">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-neutral-900">Manual Backup</p>
                    <p class="text-xs text-neutral-500">Create a new backup that includes content, leads, and metadata.</p>
                </div>
                <button class="btn-primary" @click="createBackup()">Create Backup</button>
            </div>

            <div class="adm-table-wrap">
                <table class="adm-table">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-4 py-3">Backup Name</th>
                            <th class="px-4 py-3">Created At</th>
                            <th class="px-4 py-3">Size</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 bg-white">
                        <template x-for="item in backups" :key="item.id">
                            <tr>
                                <td class="px-4 py-3 font-medium text-neutral-900" x-text="item.name"></td>
                                <td class="px-4 py-3 text-neutral-600" x-text="item.createdAt"></td>
                                <td class="px-4 py-3 text-neutral-600" x-text="item.size"></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700" x-text="item.status"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <button class="text-sm font-medium text-neutral-700 hover:text-neutral-900">Download</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="adm-card p-5">
            <h3 class="text-base font-semibold text-neutral-900">Backup Settings</h3>
            <div class="mt-4 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="admin-label">Auto Backup Frequency</label>
                    <select class="admin-input">
                        <option>Daily</option>
                        <option>Weekly</option>
                        <option>Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="admin-label">Retention Policy</label>
                    <select class="admin-input">
                        <option>Keep last 7 backups</option>
                        <option>Keep last 30 backups</option>
                        <option>Keep all backups</option>
                    </select>
                </div>
            </div>
        </section>

        <div class="fixed bottom-6 right-6 z-50">
            <div x-show="toast" x-transition class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-lg" style="display: none;">
                Backup created successfully.
            </div>
        </div>
    </div>

    <script>
        function backupCenter() {
            return {
                toast: false,
                backups: [
                    { id: 1, name: 'backup_2026_07_20_0900.zip', createdAt: 'Jul 20, 2026 09:00', size: '48 MB', status: 'Ready' },
                    { id: 2, name: 'backup_2026_07_19_0900.zip', createdAt: 'Jul 19, 2026 09:00', size: '47 MB', status: 'Ready' },
                ],
                createBackup() {
                    const now = new Date();
                    const label = now.toISOString().slice(0, 16).replace(/[-:T]/g, '_');
                    this.backups.unshift({
                        id: crypto.randomUUID(),
                        name: `backup_${label}.zip`,
                        createdAt: now.toLocaleString(),
                        size: '49 MB',
                        status: 'Ready',
                    });
                    this.toast = true;
                    setTimeout(() => this.toast = false, 10000);
                },
            };
        }
    </script>
</x-layouts.admin>
