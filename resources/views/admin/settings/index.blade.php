<x-layouts.admin title="Settings">
    <div class="space-y-6" x-data="{ toast: false }">
        <section class="admin-card p-5">
            <h2 class="text-lg font-semibold text-neutral-900">Platform Settings</h2>
            <p class="mt-1 text-sm text-neutral-500">Control admin defaults, notifications, and display preferences.</p>
        </section>

        <section class="admin-card p-5">
            <form class="space-y-5" @submit.prevent="toast = true; setTimeout(() => toast = false, 10000)">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="admin-label">Default Currency</label>
                        <select class="admin-input">
                            <option>USD</option>
                            <option>EUR</option>
                            <option>EGP</option>
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Default Timezone</label>
                        <select class="admin-input">
                            <option>Africa/Cairo</option>
                            <option>Europe/Athens</option>
                            <option>UTC</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="admin-label">Notification Preferences</label>
                    <label class="flex items-center gap-2 text-sm text-neutral-700">
                        <input type="checkbox" class="rounded border-neutral-300" checked>
                        Email alerts for new inquiries
                    </label>
                    <label class="flex items-center gap-2 text-sm text-neutral-700">
                        <input type="checkbox" class="rounded border-neutral-300" checked>
                        Alerts for site visit confirmations
                    </label>
                    <label class="flex items-center gap-2 text-sm text-neutral-700">
                        <input type="checkbox" class="rounded border-neutral-300">
                        Daily summary report
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.settings.backup') }}" class="btn-secondary">Open Data Backup</a>
                    <button type="submit" class="btn-primary">Save Settings</button>
                </div>
            </form>
        </section>

        <div class="fixed bottom-6 right-6 z-50">
            <div x-show="toast" x-transition class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-lg" style="display: none;">
                Settings saved successfully.
            </div>
        </div>
    </div>
</x-layouts.admin>
