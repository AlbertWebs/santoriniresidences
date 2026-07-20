<x-layouts.admin title="Profile">
    <div class="space-y-6">
        <section class="admin-card p-5">
            <h2 class="text-lg font-semibold text-neutral-900">My Profile</h2>
            <p class="mt-1 text-sm text-neutral-500">Manage your account identity and contact details.</p>
        </section>

        <section class="admin-card p-5">
            <form class="grid gap-5 md:grid-cols-2" x-data="{ toast: false }" @submit.prevent="toast = true; setTimeout(() => toast = false, 10000)">
                <div>
                    <label class="admin-label">Full Name</label>
                    <input type="text" class="admin-input" value="Santorini Administrator">
                </div>
                <div>
                    <label class="admin-label">Email</label>
                    <input type="email" class="admin-input" value="admin@santoriniresidences.com">
                </div>
                <div>
                    <label class="admin-label">Phone</label>
                    <input type="text" class="admin-input" value="+20 100 555 2233">
                </div>
                <div>
                    <label class="admin-label">Role</label>
                    <input type="text" class="admin-input" value="Super Admin">
                </div>
                <div class="md:col-span-2">
                    <label class="admin-label">Bio</label>
                    <textarea rows="4" class="admin-input">Leading operations for off-plan projects, content governance, and sales coordination.</textarea>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="btn-primary">Save Profile</button>
                </div>

                <div class="fixed bottom-6 right-6 z-50">
                    <div x-show="toast" x-transition class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-lg" style="display: none;">
                        Profile updated successfully.
                    </div>
                </div>
            </form>
        </section>
    </div>
</x-layouts.admin>
