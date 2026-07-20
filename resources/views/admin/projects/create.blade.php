<x-layouts.admin title="Housing Projects / Units Wizard">
    <div class="space-y-6" x-data="projectWizard()">
        <section class="admin-card p-5">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Project Details</h2>
                    <p class="text-sm text-neutral-500">Create and manage off-plan projects and unit inventory with premium media content.</p>
                </div>
                <div class="flex gap-2">
                    <button class="btn-secondary" @click="showPricingModal = true">Quick Edit Unit Pricing</button>
                    <button class="btn-primary" @click="showToast('Project saved successfully', 'success')">Save Project</button>
                </div>
            </div>

            <form class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="admin-label">Project Title</label>
                    <input type="text" class="admin-input" placeholder="Aegean Crown Villas">
                </div>
                <div>
                    <label class="admin-label">Location</label>
                    <input type="text" class="admin-input" placeholder="North Coast, Egypt">
                </div>
                <div>
                    <label class="admin-label">Price Range</label>
                    <input type="text" class="admin-input" placeholder="$250,000 - $1,200,000">
                </div>
                <div>
                    <label class="admin-label">Completion Date</label>
                    <input type="date" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Construction Status</label>
                    <select class="admin-input">
                        <option>Planning</option>
                        <option>Foundation</option>
                        <option>Shell</option>
                        <option>Finishes</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="admin-label">Description</label>
                    <textarea rows="4" class="admin-input" placeholder="Write project positioning, architecture language, and handover schedule details..."></textarea>
                </div>
            </form>
        </section>

        <section class="admin-card p-5">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-neutral-900">Dynamic Room & Image Gallery</h3>
                    <p class="text-sm text-neutral-500">Add rooms and upload multiple images with drag-and-drop previews.</p>
                </div>
                <button class="btn-secondary" @click="addRoom()">Add Room</button>
            </div>

            <div class="space-y-4">
                <template x-for="(room, roomIndex) in rooms" :key="room.id">
                    <div class="rounded-2xl border border-neutral-200 p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <input type="text" class="admin-input max-w-sm" x-model="room.name" placeholder="Room name e.g. Master Bedroom">
                            <button type="button" class="text-sm text-rose-600 hover:text-rose-700" @click="removeRoom(room.id)">Remove</button>
                        </div>

                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-neutral-300 bg-neutral-50 px-4 py-8 text-center transition hover:border-neutral-500 hover:bg-neutral-100">
                            <svg class="mb-2 h-6 w-6 text-neutral-500" viewBox="0 0 24 24" fill="none"><path d="M12 16V8m0 0-3 3m3-3 3 3M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <p class="text-sm font-medium text-neutral-700">Drop room images here or click to upload</p>
                            <p class="text-xs text-neutral-500">PNG, JPG up to 8MB each</p>
                            <input type="file" class="hidden" multiple accept="image/*" @change="handleFiles($event, roomIndex)">
                        </label>

                        <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <template x-for="(image, imageIndex) in room.images" :key="image.id">
                                <div class="relative overflow-hidden rounded-xl border border-neutral-200">
                                    <img :src="image.url" alt="Room image preview" class="h-28 w-full object-cover">
                                    <button type="button" class="absolute right-2 top-2 rounded-full bg-black/60 p-1 text-white"
                                        @click="room.images.splice(imageIndex, 1)">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        <section class="admin-card p-5">
            <h3 class="text-base font-semibold text-neutral-900">Interactive VR & 3D Tour Embedding</h3>
            <p class="mb-4 text-sm text-neutral-500">Paste any trusted iFrame embed code and preview instantly.</p>

            <div class="grid gap-5 lg:grid-cols-2">
                <div class="space-y-4">
                    <div>
                        <label class="admin-label">VR Tour Provider</label>
                        <select class="admin-input" x-model="vr.provider">
                            <option value="Matterport">Matterport</option>
                            <option value="Kuula">Kuula</option>
                            <option value="Custom iFrame">Custom iFrame</option>
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">iFrame Embed Code</label>
                        <textarea rows="8" class="admin-input font-mono text-xs" x-model="vr.embedCode" placeholder='<iframe src="https://example.com/tour"></iframe>'></textarea>
                    </div>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-neutral-700">Live Preview</p>
                    <div class="aspect-video overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-100">
                        <template x-if="sanitizedEmbed">
                            <div class="h-full w-full" x-html="sanitizedEmbed"></div>
                        </template>
                        <template x-if="!sanitizedEmbed">
                            <div class="flex h-full items-center justify-center text-center text-sm text-neutral-500">
                                Paste a valid iFrame embed to preview your VR/3D tour.
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <section class="admin-card overflow-hidden">
            <div class="flex items-center justify-between border-b border-neutral-200 px-5 py-4">
                <h3 class="text-base font-semibold text-neutral-900">Units Listing</h3>
                <button class="btn-secondary" @click="showMediaModal = true">View Uploaded Media</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Unit</th>
                            <th class="px-5 py-3">Type</th>
                            <th class="px-5 py-3">Base Price</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 bg-white">
                        <template x-for="unit in units" :key="unit.id">
                            <tr class="hover:bg-neutral-50/60">
                                <td class="px-5 py-4 font-medium text-neutral-900" x-text="unit.name"></td>
                                <td class="px-5 py-4 text-neutral-600" x-text="unit.type"></td>
                                <td class="px-5 py-4 text-neutral-600" x-text="unit.price"></td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                                        :class="unit.status === 'Selling Fast' ? 'bg-amber-100 text-amber-800' : (unit.status === 'Sold Out' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700')"
                                        x-text="unit.status"></span>
                                </td>
                                <td class="px-5 py-4">
                                    <button class="text-sm font-medium text-neutral-700 hover:text-neutral-900" @click="editPrice(unit)">Edit Price</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </section>

        <div x-show="showPricingModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
            <div x-transition class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl">
                <h4 class="text-lg font-semibold text-neutral-900">Quick Edit Pricing</h4>
                <p class="mt-1 text-sm text-neutral-500" x-text="editingUnit ? editingUnit.name : 'Select a unit'"></p>
                <div class="mt-4">
                    <label class="admin-label">Updated Price</label>
                    <input type="text" class="admin-input" x-model="editingPrice">
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button class="btn-secondary" @click="showPricingModal = false">Cancel</button>
                    <button class="btn-primary" @click="applyPrice()">Save</button>
                </div>
            </div>
        </div>

        <div x-show="showMediaModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
            <div x-transition class="w-full max-w-3xl rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-neutral-900">Uploaded Media Gallery</h4>
                    <button class="rounded-lg p-1 text-neutral-500 hover:bg-neutral-100" @click="showMediaModal = false">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    <template x-for="preview in allImages" :key="preview.id">
                        <img :src="preview.url" alt="Uploaded media" class="h-24 w-full rounded-xl border border-neutral-200 object-cover">
                    </template>
                </div>
            </div>
        </div>

        <div class="fixed bottom-6 right-6 z-50 space-y-2">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast.visible" x-transition
                    class="rounded-xl border px-4 py-3 text-sm shadow-lg"
                    :class="toast.type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700'">
                    <p x-text="toast.message"></p>
                </div>
            </template>
        </div>
    </div>

    <script>
        function projectWizard() {
            return {
                showPricingModal: false,
                showMediaModal: false,
                editingUnit: null,
                editingPrice: '',
                rooms: [
                    { id: crypto.randomUUID(), name: 'Master Bedroom', images: [] },
                    { id: crypto.randomUUID(), name: 'Living Room', images: [] },
                ],
                vr: {
                    provider: 'Matterport',
                    embedCode: ''
                },
                units: [
                    { id: 1, name: 'A-101', type: '2 Bedroom Apartment', price: '$320,000', status: 'Selling Fast' },
                    { id: 2, name: 'B-305', type: '3 Bedroom Duplex', price: '$540,000', status: 'Pre-Launch' },
                    { id: 3, name: 'C-002', type: 'Townhouse', price: '$610,000', status: 'Sold Out' },
                ],
                toasts: [],
                get sanitizedEmbed() {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(this.vr.embedCode, 'text/html');
                    const iframe = doc.querySelector('iframe');
                    if (!iframe) {
                        return '';
                    }

                    const src = iframe.getAttribute('src') || '';
                    if (!src.startsWith('http://') && !src.startsWith('https://')) {
                        return '';
                    }

                    iframe.setAttribute('class', 'h-full w-full border-0');
                    iframe.setAttribute('loading', 'lazy');
                    iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
                    return iframe.outerHTML;
                },
                get allImages() {
                    return this.rooms.flatMap((room) => room.images);
                },
                addRoom() {
                    this.rooms.push({
                        id: crypto.randomUUID(),
                        name: '',
                        images: [],
                    });
                },
                removeRoom(roomId) {
                    this.rooms = this.rooms.filter((room) => room.id !== roomId);
                },
                handleFiles(event, roomIndex) {
                    const files = Array.from(event.target.files ?? []);
                    if (!files.length) {
                        return;
                    }

                    files.forEach((file) => {
                        const fileReader = new FileReader();
                        fileReader.onload = (loadEvent) => {
                            this.rooms[roomIndex].images.push({
                                id: crypto.randomUUID(),
                                url: loadEvent.target.result,
                                name: file.name,
                            });
                        };
                        fileReader.readAsDataURL(file);
                    });
                },
                editPrice(unit) {
                    this.editingUnit = unit;
                    this.editingPrice = unit.price;
                    this.showPricingModal = true;
                },
                applyPrice() {
                    if (!this.editingUnit || !this.editingPrice.trim()) {
                        this.showToast('Please enter a valid price', 'error');
                        return;
                    }
                    this.editingUnit.price = this.editingPrice.trim();
                    this.showPricingModal = false;
                    this.showToast('Unit price updated successfully', 'success');
                },
                showToast(message, type = 'success') {
                    const id = crypto.randomUUID();
                    this.toasts.push({ id, message, type, visible: true });
                    setTimeout(() => {
                        const toast = this.toasts.find((item) => item.id === id);
                        if (toast) toast.visible = false;
                    }, 10000);
                    setTimeout(() => {
                        this.toasts = this.toasts.filter((item) => item.id !== id);
                    }, 10600);
                },
            };
        }
    </script>
</x-layouts.admin>
